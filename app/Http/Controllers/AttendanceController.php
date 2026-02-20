<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Method 1: Submit Location
     * Employee sends their current lat/lng to be matched against session location.
     */
    public function submitLocation(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $session = AttendanceSession::findOrFail($request->session_id);

        // 1. Check if session is active
        $now = Carbon::now();
        if ($now->lt($session->start_time) || $now->gt($session->end_time)) {
            return back()->with('error', 'Session is not active.');
        }

        // 2. Check method
        if ($session->method !== 'location') {
            return back()->with('error', 'Invalid method for this session.');
        }

        // 3. Calculate distance (Haversine formula)
        $distance = $this->calculateDistance(
            $request->latitude, $request->longitude,
            $session->latitude, $session->longitude
        );

        if ($distance > $session->radius) {
            return back()->with('error', "You are outside the radius ($distance meters away).");
        }

        // 4. Log Presence
        $this->recordAttendance($session, Auth::id(), [
            'lat' => $request->latitude,
            'lng' => $request->longitude,
            'method_used' => 'location'
        ]);

        return back()->with('success', 'Presence recorded successfully!');
    }

    /**
     * Method 2: Share QR (Employee scans Admin's QR)
     * Token is passed from the URL scanned.
     */
    public function scanQRByEmployee($token)
    {
        $session = AttendanceSession::where('qr_token', $token)->firstOrFail();

        $now = Carbon::now();
        if ($now->lt($session->start_time) || $now->gt($session->end_time)) {
            return redirect()->route('dashboard')->with('error', 'Session is not active.');
        }

        $this->recordAttendance($session, Auth::id(), [
            'method_used' => 'share_qr'
        ]);

        return redirect()->route('dashboard')->with('success', 'Presence recorded via QR scan!');
    }

    private function extractNip($token)
    {
        // If it's a URL, extract the last segment
        if (filter_var($token, FILTER_VALIDATE_URL)) {
            $path = parse_url($token, PHP_URL_PATH);
            $segments = explode('/', trim($path, '/'));
            return end($segments);
        }

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
                
                // Extract Fullname (usually in a bold div/h4)
                // Based on standard BPS badge structure
                preg_match('/<div style="font-size: 20px; font-weight: bold;[^>]*>(.*?)<\/div>/s', $html, $nameMatch);
                $fullname = isset($nameMatch[1]) ? trim($nameMatch[1]) : null;

                if ($fullname) {
                    // Search user by name in our database
                    $user = \App\Models\User::where('fullname', 'LIKE', '%' . $fullname . '%')
                        ->orWhere('name', 'LIKE', '%' . $fullname . '%')
                        ->first();

                    if ($user) {
                        // Map this badge_id for future scans
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

    /**
     * Method 3: Scan User's QR (Admin scans Employee's QR)
     * Admin submits the User's NIP/ID.
     */
    public function adminScanUserQR(Request $request)
    {
        $request->validate([
            'user_token' => 'required', // This would be decrypted/decoded from user's QR
            'session_id' => 'required|exists:attendance_sessions,id',
        ]);

        $session = AttendanceSession::findOrFail($request->session_id);
        
        // Check if session is active
        $now = Carbon::now();
        if ($now->lt($session->start_time) || $now->gt($session->end_time)) {
            return response()->json(['success' => false, 'message' => 'Sesi kegiatan tidak aktif.']);
        }

        // Check if method is correct
        if ($session->method !== 'scan_qr') {
            return response()->json(['success' => false, 'message' => 'Metode presensi tidak sesuai.']);
        }
        
        // Find user by token
        $userToken = $request->user_token;
        $user = null;

        if (filter_var($userToken, FILTER_VALIDATE_URL) && strpos($userToken, 'badgebps.web.bps.go.id') !== false) {
            $user = $this->resolveUserFromBadgeUrl($userToken);
        } else {
            $userToken = $this->extractNip($userToken);
            $user = \App\Models\User::where('nip_lama', $userToken)
                ->orWhere('nip_baru', $userToken)
                ->orWhere('badge_id', $userToken)
                ->first();
        }

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pegawai tidak ditemukan atau tidak dapat divalidasi dari Badge BPS.']);
        }

        // Check if already recorded
        $existing = Attendance::where('user_id', $user->id)
            ->where('attendance_session_id', $session->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false, 
                'message' => 'Pegawai sudah melakukan presensi untuk kegiatan ini.',
                'user_name' => $user->fullname ?? $user->name,
                'user_nip' => $user->nip_lama
            ]);
        }

        $this->recordAttendance($session, $user->id, [
            'method_used' => 'scan_qr'
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Presensi berhasil dicatat untuk ' . ($user->fullname ?? $user->name),
            'user_name' => $user->fullname ?? $user->name,
            'user_nip' => $user->nip_lama
        ]);
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
