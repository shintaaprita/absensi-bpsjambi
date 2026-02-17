<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = AttendanceSession::latest()->paginate(10);
        return view('admin.sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('admin.sessions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'method' => 'required|in:location,share_qr,scan_qr',
            'location_name' => 'required_if:method,location',
            'latitude' => 'required_if:method,location',
            'longitude' => 'required_if:method,location',
            'radius' => 'nullable|numeric|min:10',
        ]);

        $validated['qr_token'] = Str::random(32);

        AttendanceSession::create($validated);

        return redirect()->route('admin.sessions.index')->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function show(AttendanceSession $session)
    {
        $session->load('attendances.user');
        return view('admin.sessions.show', compact('session'));
    }

    public function destroy(AttendanceSession $session)
    {
        $session->delete();
        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}
