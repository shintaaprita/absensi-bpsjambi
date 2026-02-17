<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();
        
        // Check if user is admin
        $isAdmin = in_array(1, session('roles', [])) || $user->username == 'admin';
        
        if ($isAdmin) {
            // Admin Dashboard
            return $this->adminDashboard($now);
        } else {
            // Employee Dashboard
            return $this->employeeDashboard($now);
        }
    }

    private function adminDashboard($now)
    {
        // Total users
        $totalUsers = \App\Models\User::count();

        // Total sessions
        $totalSessions = AttendanceSession::count();

        // Active sessions
        $activeSessions = AttendanceSession::where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->withCount('attendances')
            ->orderBy('start_time', 'asc')
            ->get();

        // Today's attendances count
        $todayAttendances = Attendance::whereDate('captured_at', Carbon::today())->count();

        // Monthly attendances count
        $monthlyAttendances = Attendance::whereMonth('captured_at', Carbon::now()->month)
            ->whereYear('captured_at', Carbon::now()->year)
            ->count();

        // Recent attendances (last 20)
        $recentAttendances = Attendance::whereDate('captured_at', Carbon::today())
            ->with('user', 'session')
            ->orderBy('captured_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSessions',
            'activeSessions',
            'todayAttendances',
            'monthlyAttendances',
            'recentAttendances'
        ));
    }

    private function employeeDashboard($now)
    {
        // Active sessions that started and haven't ended
        $activeSessions = AttendanceSession::where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->orderBy('start_time', 'asc')
            ->get();

        // Upcoming sessions (today and future)
        $upcomingSessions = AttendanceSession::where('start_time', '>', $now)
            ->whereDate('start_time', '>=', Carbon::today())
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();

        // User's attendance today
        $myAttendances = Attendance::where('user_id', Auth::id())
            ->whereDate('captured_at', Carbon::today())
            ->with('session')
            ->orderBy('captured_at', 'desc')
            ->get();

        // User's total attendance this month
        $monthlyAttendanceCount = Attendance::where('user_id', Auth::id())
            ->whereMonth('captured_at', Carbon::now()->month)
            ->whereYear('captured_at', Carbon::now()->year)
            ->count();

        // Total sessions this month
        $totalSessionsThisMonth = AttendanceSession::whereMonth('start_time', Carbon::now()->month)
            ->whereYear('start_time', Carbon::now()->year)
            ->count();

        return view('dashboard', compact(
            'activeSessions', 
            'upcomingSessions',
            'myAttendances', 
            'monthlyAttendanceCount',
            'totalSessionsThisMonth'
        ));
    }
}
