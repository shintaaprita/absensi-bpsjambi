<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $sessionId = $request->input('session_id');
        $method = $request->input('method');

        // Build query
        $query = Attendance::with(['user', 'session'])
            ->whereBetween('captured_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        // Apply filters
        if ($sessionId) {
            $query->where('attendance_session_id', $sessionId);
        }

        if ($method) {
            $query->where('method_used', $method);
        }

        // Get paginated results
        $attendances = $query->orderBy('captured_at', 'desc')->paginate(20);

        // Get statistics
        $uniqueUsers = Attendance::whereBetween('captured_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->when($sessionId, function($q) use ($sessionId) {
                return $q->where('attendance_session_id', $sessionId);
            })
            ->when($method, function($q) use ($method) {
                return $q->where('method_used', $method);
            })
            ->distinct('user_id')
            ->count('user_id');

        $uniqueSessions = Attendance::whereBetween('captured_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->when($sessionId, function($q) use ($sessionId) {
                return $q->where('attendance_session_id', $sessionId);
            })
            ->when($method, function($q) use ($method) {
                return $q->where('method_used', $method);
            })
            ->distinct('attendance_session_id')
            ->count('attendance_session_id');

        // Get all sessions for filter dropdown
        $sessions = AttendanceSession::orderBy('start_time', 'desc')->get();

        return view('admin.reports', compact(
            'attendances',
            'sessions',
            'uniqueUsers',
            'uniqueSessions'
        ));
    }
}
