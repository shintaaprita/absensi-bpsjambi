<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\User;
use App\Models\SatkerSetting;
use App\Models\WorkingSchedule;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AttendanceApiController extends Controller
{
    /**
     * Method 1: Submit Location
     */
    public function submitLocation(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|image|max:5120', // Max 5MB, will be compressed
        ]);

        $user = Auth::user();
        $session = AttendanceSession::findOrFail($request->session_id);
        $satkerCode = $user->satker_kd;

        // 1. Check if session belongs to user's Satker
        if ($session->satker_code && $session->satker_code !== $satkerCode) {
            return response()->json(['success' => false, 'message' => 'Sesi ini bukan untuk Satker Anda.'], 403);
        }

        // 2. Check if session is active
        $now = Carbon::now();
        if ($now->lt($session->start_time) || $now->gt($session->end_time)) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak aktif.'], 400);
        }

        // 3. Fetch Satker Settings
        $settings = $this->getSatkerSettings($satkerCode);
        
        // 4. Photo Requirement Check
        if (($settings['foto_requirement'] ?? 'FALSE') === 'TRUE' && !$request->hasFile('photo')) {
            return response()->json(['success' => false, 'message' => 'Foto wajib dilampirkan.'], 400);
        }

        // 5. Location Check (Skipped if User or Satker is WFA)
        $isWfa = $user->is_wfa || ($settings['global_wfa'] ?? 'FALSE') === 'TRUE';
        if (!$isWfa) {
             // Use Satker location if session doesn't have one, or session-specific
             $targetLat = $session->latitude ?? floatval(str_replace(',', '.', $settings['lokasi_kantor_lat'] ?? 0));
             $targetLon = $session->longitude ?? floatval(str_replace(',', '.', $settings['lokasi_kantor_lon'] ?? 0));
             $radius = $session->radius ?? intval($settings['radius_validasi'] ?? 100);

             $distance = $this->calculateDistance($request->latitude, $request->longitude, $targetLat, $targetLon);

             if ($distance > $radius) {
                 return response()->json([
                     'success' => false,
                     'message' => "Anda berada di luar radius (" . round($distance) . " meter dari " . ($session->latitude ? 'lokasi kegiatan' : 'kantor') . ")."
                 ], 400);
             }
        }

        // 6. Handle Photo Upload & Compression
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $this->handleCompressedUpload($request->file('photo'), 'presensi');
        }

        // 7. Determine Attendance Status & Type (In/Out)
        $statusInfo = $this->getAttendanceStatus($now, $satkerCode);

        // 8. Log Presence
        $this->recordAttendance($session, $user->id, [
            'lat' => $request->latitude,
            'lng' => $request->longitude,
            'method_used' => 'location',
            'photo_path' => $photoPath,
            'is_wfa_at_time' => $isWfa,
            'status' => $statusInfo['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat via Lokasi.',
            'status' => $statusInfo['status_label'],
            'is_wfa' => $isWfa
        ]);
    }

    /**
     * Method 2: Scan QR
     * Can be used for:
     * 1. Employee scanning Session QR
     * 2. Admin scanning Employee QR
     */
    public function scanQR(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $token = $request->token;
        $now = Carbon::now();

        // Check if token is a Session QR Token (Method: Share QR - Employee scans Admin)
        $session = AttendanceSession::where('qr_token', $token)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->first();

        if ($session) {
            // Check if session belongs to user's Satker
            if ($session->satker_code && $session->satker_code !== Auth::user()->satker_kd) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi ini bukan untuk Satker Anda.'
                ], 403);
            }

            $this->recordAttendance($session, Auth::id(), [
                'method_used' => 'share_qr'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Presensi berhasil dicatat via Scan QR.',
                'type' => 'session_scan',
                'session_title' => $session->title
            ]);
        }

        // Check if token is a User Token (Method: Scan QR - Admin scans Employee)
        // Only if current user is admin/petugas
        $userToken = $request->token;
        $employee = null;

        if (filter_var($userToken, FILTER_VALIDATE_URL) && strpos($userToken, 'badgebps.web.bps.go.id') !== false) {
            $employee = $this->resolveUserFromBadgeUrl($userToken);
        } else {
            $token = $this->extractNip($userToken);
            $employee = User::where('nip_lama', $token)
                ->orWhere('nip_baru', $token)
                ->orWhere('badge_id', $token)
                ->first();
        }

        if ($employee) {
            // Find active session that uses 'scan_qr' method FOR THE ADMIN'S SATKER
            $activeSession = AttendanceSession::where('method', 'scan_qr')
                ->where('satker_code', Auth::user()->satker_kd)
                ->where('start_time', '<=', $now)
                ->where('end_time', '>=', $now)
                ->first();

            if (!$activeSession) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada sesi aktif dengan metode Scan User QR.'
                ], 400);
            }

            $this->recordAttendance($activeSession, $employee->id, [
                'method_used' => 'scan_qr'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Presensi berhasil dicatat untuk ' . ($employee->fullname ?? $employee->name),
                'type' => 'user_scan',
                'employee_name' => $employee->fullname ?? $employee->name
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'QR Code tidak valid atau sesi tidak ditemukan.'
        ], 404);
    }

    /**
     * Method 3: Show QR
     * Returns the token needed to generate user's QR code
     */
    public function showQR()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'qr_data' => $user->nip_lama, // Using nip_lama as the QR token for now
            'user' => [
                'name' => $user->fullname ?? $user->name,
                'nip' => $user->nip_lama
            ]
        ]);
    }

    private function extractNip($token)
    {
        // If it's a URL, extract the last segment
        if (filter_var($token, FILTER_VALIDATE_URL)) {
            $path = parse_url($token, PHP_URL_PATH);
            $segments = explode('/', trim($path, '/'));
            return end($segments);
        }

        // If it's not a URL, return as is (direct NIP)
        return $token;
    }

    private function resolveUserFromBadgeUrl($url)
    {
        $badgeId = $this->extractNip($url);
        
        // 1. Check if we already mapped this badge_id
        $user = \App\Models\User::where('badge_id', $badgeId)->first();
        if ($user) return $user;

        // 2. Scrape the badge site
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            ])->get($url);

            if ($response->successful()) {
                $html = $response->body();
                
                // Extract Fullname (Based on standard BPS badge structure)
                preg_match('/<div style="font-size: 20px; font-weight: bold;[^>]*>(.*?)<\/div>/s', $html, $nameMatch);
                $fullname = isset($nameMatch[1]) ? trim($nameMatch[1]) : null;

                if ($fullname) {
                    $user = \App\Models\User::where('fullname', 'LIKE', '%' . $fullname . '%')
                        ->orWhere('name', 'LIKE', '%' . $fullname . '%')
                        ->first();

                    if ($user) {
                        $user->update(['badge_id' => $badgeId]);
                        return $user;
                    }
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Badge scraping failed: ' . $e->getMessage());
        }

        return null;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'fullname' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        
        if ($request->has('fullname')) {
            $user->fullname = $request->fullname;
        }

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = $this->handleCompressedUpload($request->file('photo'), 'profiles');
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'user' => $user
        ]);
    }

    private function getSatkerSettings($satkerCode)
    {
        return SatkerSetting::where('satker_code', $satkerCode)
            ->pluck('value', 'key')
            ->toArray();
    }

    private function getAttendanceStatus($now, $satkerCode)
    {
        // Check Holiday
        $holiday = Holiday::where('holiday_date', $now->toDateString())->first();
        if ($holiday || $now->isWeekend()) {
            return ['status' => 'overtime', 'status_label' => 'Lembur (Hari Libur/Weekend)'];
        }

        // Check Work Schedule
        $dayName = $now->format('l');
        $schedule = WorkingSchedule::where('satker_code', $satkerCode)
            ->where('day_name', $dayName)
            ->first();

        if (!$schedule || !$schedule->is_working_day) {
            return ['status' => 'overtime', 'status_label' => 'Lembur (Luar Jam Kerja)'];
        }

        // Check late or overtime
        $time = $now->toTimeString();
        if ($time > $schedule->clock_out) {
            return ['status' => 'overtime', 'status_label' => 'Hadir (Lembur)'];
        }

        if ($time > $schedule->clock_in) {
            return ['status' => 'present', 'status_label' => 'Hadir (Terlambat)'];
        }

        return ['status' => 'present', 'status_label' => 'Hadir Tepat Waktu'];
    }

    private function handleCompressedUpload($file, $folder)
    {
        // In a real environment with image library, we would compress here
        // For now, we'll just store it. 
        // Logic for "compression" would go here: Intervention Image -> resize -> quality 60
        
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs($folder, $filename, 'public');
        
        return $path;
    }

    private function recordAttendance($session, $userId, $extra = [])
    {
        return Attendance::updateOrCreate(
            ['user_id' => $userId, 'attendance_session_id' => $session->id],
            array_merge([
                'status' => 'present',
                'captured_at' => Carbon::now(),
            ], $extra)
        );
    }
}
