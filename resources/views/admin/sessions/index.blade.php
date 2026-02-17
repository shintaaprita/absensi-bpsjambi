@extends('layouts.app')

@section('content')
<div class="animate-fade">
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 700;">Manajemen Kegiatan</h1>
            <p style="color: var(--text-muted);">Kelola sesi presensi dan pantau kehadiran</p>
        </div>
        <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary">
            + Tambah Kegiatan
        </a>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--bg-main); border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-muted);">Judul</th>
                    <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-muted);">Waktu</th>
                    <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-muted);">Metode</th>
                    <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-muted);">Hadir</th>
                    <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-muted);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 1.2rem 1.5rem;">
                            <div style="font-weight: 600;">{{ $session->title }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">{{ Str::limit($session->description, 50) }}</div>
                        </td>
                        <td style="padding: 1.2rem 1.5rem;">
                            <div style="font-size: 0.9rem;">{{ $session->start_time->format('d M, H:i') }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $session->end_time->format('H:i') }}</div>
                        </td>
                        <td style="padding: 1.2rem 1.5rem;">
                            <span style="font-size: 0.85rem; background: var(--border); padding: 0.2rem 0.6rem; border-radius: 4px;">
                                {{ strtoupper(str_replace('_', ' ', $session->method)) }}
                            </span>
                        </td>
                        <td style="padding: 1.2rem 1.5rem;">
                            <div style="font-weight: 700; color: var(--primary);">{{ $session->attendances_count ?? $session->attendances()->count() }}</div>
                        </td>
                        <td style="padding: 1.2rem 1.5rem;">
                            <div class="flex gap-4">
                                <a href="{{ route('admin.sessions.show', $session) }}" class="nav-link" style="font-size: 0.9rem; color: var(--primary);">Detail</a>
                                <form action="{{ route('admin.sessions.destroy', $session) }}" method="POST" onsubmit="return confirm('Hapus kegiatan?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; font-size: 0.9rem;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-muted);">Belum ada kegiatan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
