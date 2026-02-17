<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScanQRController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Get active sessions with scan_qr method
        $activeSessions = AttendanceSession::where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->where('method', 'scan_qr')
            ->orderBy('start_time', 'asc')
            ->get();

        // Get recent attendances today
        $recentAttendances = Attendance::whereDate('captured_at', Carbon::today())
            ->where('method_used', 'scan_qr')
            ->with('user', 'session')
            ->orderBy('captured_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.scan-qr', compact('activeSessions', 'recentAttendances'));
    }

    public function showQR($sessionId = null)
    {
        $now = Carbon::now();

        // Get active sessions with share_qr method
        $activeSessions = AttendanceSession::where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->where('method', 'share_qr')
            ->orderBy('start_time', 'asc')
            ->get();

        // Get selected session or first active session
        $session = null;
        if ($sessionId) {
            $session = AttendanceSession::find($sessionId);
        } elseif ($activeSessions->isNotEmpty()) {
            $session = $activeSessions->first();
        }

        return view('admin.show-qr', compact('activeSessions', 'session'));
    }
}
