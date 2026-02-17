@extends('layouts.app')

@section('content')
<div class="animate-fade" id="printArea">
    <header style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 2rem; font-weight: 700;">Laporan Presensi</h1>
        <p style="color: var(--text-muted);">Lihat dan export laporan presensi pegawai</p>
    </header>

    <!-- Filter Summary (Hidden on screen, shown on print) -->
    <div class="filter-summary">
        <strong>Periode:</strong> {{ request('start_date', date('d/m/Y', strtotime(date('Y-m-01')))) }} - {{ request('end_date', date('d/m/Y')) }}
        @if(request('session_id'))
            | <strong>Kegiatan:</strong> {{ $sessions->find(request('session_id'))->title ?? '-' }}
        @endif
        @if(request('method'))
            | <strong>Metode:</strong> 
            @if(request('method') == 'scan_qr') Scan QR
            @elseif(request('method') == 'location') Submit Location
            @elseif(request('method') == 'share_qr') Share QR
            @endif
        @endif
    </div>

    <!-- Filter Section -->
    <div class="card" style="margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 24px; height: 24px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Filter Laporan
        </h3>
        
        <form method="GET" action="{{ route('admin.reports') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--text);">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date', date('Y-m-01')) }}" class="input" style="width: 100%;">
            </div>
            
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--text);">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}" class="input" style="width: 100%;">
            </div>
            
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--text);">Kegiatan</label>
                <select name="session_id" class="input" style="width: 100%;">
                    <option value="">Semua Kegiatan</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                            {{ $session->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--text);">Metode</label>
                <select name="method" class="input" style="width: 100%;">
                    <option value="">Semua Metode</option>
                    <option value="scan_qr" {{ request('method') == 'scan_qr' ? 'selected' : '' }}>Scan QR</option>
                    <option value="location" {{ request('method') == 'location' ? 'selected' : '' }}>Submit Location</option>
                    <option value="share_qr" {{ request('method') == 'share_qr' ? 'selected' : '' }}>Share QR</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <svg style="width: 16px; height: 16px; display: inline; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.reports') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Presensi</p>
                    <h2 style="font-size: 2rem; font-weight: 700;">{{ $attendances->total() }}</h2>
                </div>
                <svg style="width: 40px; height: 40px; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">Pegawai Hadir</p>
                    <h2 style="font-size: 2rem; font-weight: 700;">{{ $uniqueUsers }}</h2>
                </div>
                <svg style="width: 40px; height: 40px; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>

        <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">Kegiatan</p>
                    <h2 style="font-size: 2rem; font-weight: 700;">{{ $uniqueSessions }}</h2>
                </div>
                <svg style="width: 40px; height: 40px; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Export Buttons -->
    <div style="margin-bottom: 1.5rem; display: flex; gap: 1rem; justify-content: flex-end;">
        <button onclick="exportToCSV()" class="btn btn-outline">
            <svg style="width: 16px; height: 16px; display: inline; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export CSV
        </button>
        <button onclick="window.print()" class="btn btn-outline">
            <svg style="width: 16px; height: 16px; display: inline; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Print
        </button>
    </div>

    <!-- Data Table -->
    <div class="card">
        <h3 style="margin-bottom: 1.5rem; font-weight: 600;">Data Presensi</h3>
        
        @if($attendances->isEmpty())
            <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
                <svg style="width: 64px; height: 64px; margin: 0 auto 1rem; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p style="font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem;">Tidak ada data</p>
                <p style="font-size: 0.9rem;">Coba ubah filter untuk melihat data lainnya</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table id="reportTable" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--bg-secondary); border-bottom: 2px solid var(--border);">
                            <th style="padding: 1rem; text-align: left; font-weight: 600; font-size: 0.85rem; color: var(--text);">No</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; font-size: 0.85rem; color: var(--text);">Nama Pegawai</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; font-size: 0.85rem; color: var(--text);">NIP</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; font-size: 0.85rem; color: var(--text);">Kegiatan</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; font-size: 0.85rem; color: var(--text);">Waktu</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; font-size: 0.85rem; color: var(--text);">Metode</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; font-size: 0.85rem; color: var(--text);">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $index => $attendance)
                            <tr style="border-bottom: 1px solid var(--border); transition: background 0.2s;" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 1rem; font-size: 0.9rem;">{{ $attendances->firstItem() + $index }}</td>
                                <td style="padding: 1rem; font-size: 0.9rem; font-weight: 500;">{{ $attendance->user->fullname ?? $attendance->user->name }}</td>
                                <td style="padding: 1rem; font-size: 0.9rem; color: var(--text-muted);">{{ $attendance->user->nip_lama ?? '-' }}</td>
                                <td style="padding: 1rem; font-size: 0.9rem;">{{ $attendance->session->title }}</td>
                                <td style="padding: 1rem; font-size: 0.9rem; color: var(--text-muted);">{{ $attendance->captured_at->format('d M Y, H:i:s') }}</td>
                                <td style="padding: 1rem; font-size: 0.85rem;">
                                    @if($attendance->method_used == 'scan_qr')
                                        <span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.6rem; border-radius: 100px; font-size: 0.75rem; font-weight: 600;">🔍 Scan QR</span>
                                    @elseif($attendance->method_used == 'location')
                                        <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.6rem; border-radius: 100px; font-size: 0.75rem; font-weight: 600;">📍 Location</span>
                                    @elseif($attendance->method_used == 'share_qr')
                                        <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.6rem; border-radius: 100px; font-size: 0.75rem; font-weight: 600;">📱 Share QR</span>
                                    @endif
                                </td>
                                <td style="padding: 1rem; font-size: 0.85rem;">
                                    <span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.6rem; border-radius: 100px; font-size: 0.75rem; font-weight: 600;">✓ Hadir</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Set print date when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const printArea = document.getElementById('printArea');
        if (printArea) {
            const now = new Date();
            const dateStr = now.toLocaleDateString('id-ID', { 
                day: '2-digit', 
                month: 'long', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            printArea.setAttribute('data-print-date', dateStr);
        }
    });

    // Enhanced print function
    window.onbeforeprint = function() {
        document.title = 'Laporan Presensi - BPS Jambi';
    };

    window.onafterprint = function() {
        document.title = 'Laporan - Presensi BPS';
    };

    function exportToCSV() {
        const table = document.getElementById('reportTable');
        if (!table) {
            alert('Tidak ada data untuk di-export');
            return;
        }
        
        let csv = [];
        
        // Add header info
        csv.push('"LAPORAN PRESENSI"');
        csv.push('"BPS Provinsi Jambi"');
        csv.push('""');
        
        // Add filter info
        const filterSummary = document.querySelector('.filter-summary');
        if (filterSummary) {
            csv.push('"' + filterSummary.textContent.trim().replace(/\s+/g, ' ') + '"');
        }
        csv.push('""');
        
        // Get headers
        const headers = [];
        table.querySelectorAll('thead th').forEach(th => {
            headers.push(th.textContent.trim());
        });
        csv.push(headers.join(','));
        
        // Get data
        table.querySelectorAll('tbody tr').forEach(tr => {
            const row = [];
            tr.querySelectorAll('td').forEach(td => {
                // Clean text content
                let text = td.textContent.trim();
                // Remove emojis and extra spaces
                text = text.replace(/[\u{1F300}-\u{1F9FF}]/gu, '').trim();
                // Remove multiple spaces
                text = text.replace(/\s+/g, ' ');
                // Escape quotes
                text = text.replace(/"/g, '""');
                row.push('"' + text + '"');
            });
            csv.push(row.join(','));
        });
        
        // Add footer
        csv.push('""');
        csv.push('"Dicetak pada: ' + new Date().toLocaleString('id-ID') + '"');
        
        // Download
        const csvContent = '\uFEFF' + csv.join('\r\n'); // Add BOM for Excel UTF-8 support
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', 'laporan_presensi_' + new Date().toISOString().split('T')[0] + '.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endpush

@push('styles')
<style>
    @media print {
        /* HIDE EVERYTHING EXCEPT ESSENTIAL REPORT ELEMENTS */
        nav, 
        .btn, 
        button,
        .no-print, 
        header p, 
        form, 
        .pagination,
        .card:nth-of-type(1),  /* Hide filter card */
        .card:nth-of-type(3),  /* Hide table card wrapper */
        .card h3,
        div[style*="justify-content: flex-end"],
        svg,
        .grid {  /* HIDE ALL STATISTICS CARDS */
            display: none !important;
            visibility: hidden !important;
        }
        
        /* PAGE SETUP */
        @page {
            margin: 1.5cm 1cm;
            size: A4 landscape;  /* Landscape for wider table */
        }
        
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
            color: black !important;
            font-family: 'Arial', sans-serif !important;
            font-size: 10pt !important;
        }
        
        .container {
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .animate-fade {
            animation: none !important;
        }
        
        /* ===== HEADER ===== */
        header {
            text-align: center !important;
            margin: 0 0 1rem 0 !important;
            padding: 0 0 0.75rem 0 !important;
            border-bottom: 3px solid #000 !important;
            page-break-after: avoid !important;
        }
        
        header h1 {
            font-size: 18pt !important;
            color: #000 !important;
            margin: 0 !important;
            padding: 0 !important;
            text-transform: uppercase !important;
            letter-spacing: 3px !important;
            font-weight: 700 !important;
        }
        
        header::after {
            content: "BPS Provinsi Jambi" !important;
            display: block !important;
            font-size: 11pt !important;
            color: #333 !important;
            margin-top: 0.5rem !important;
            font-weight: normal !important;
        }
        
        /* ===== PERIOD INFO ===== */
        .filter-summary {
            display: block !important;
            text-align: center !important;
            margin: 0.75rem 0 1rem 0 !important;
            padding: 0.5rem !important;
            background: #f0f0f0 !important;
            border: 1px solid #999 !important;
            font-size: 10pt !important;
            page-break-after: avoid !important;
            color: #000 !important;
        }
        
        .filter-summary strong {
            color: #000 !important;
            font-weight: 700 !important;
        }
        
        /* ===== TABLE ===== */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 9pt !important;
            margin: 0 !important;
            page-break-inside: auto !important;
        }
        
        /* Table Header */
        thead {
            display: table-header-group !important;
        }
        
        thead tr {
            background: #2c2c2c !important;
            page-break-after: avoid !important;
        }
        
        thead th {
            background: #2c2c2c !important;
            color: white !important;
            padding: 8px 6px !important;
            font-weight: 700 !important;
            font-size: 9pt !important;
            border: 1px solid #000 !important;
            text-align: left !important;
            vertical-align: middle !important;
        }
        
        /* Table Body */
        tbody tr {
            page-break-inside: avoid !important;
            page-break-after: auto !important;
        }
        
        tbody tr:nth-child(odd) {
            background: white !important;
        }
        
        tbody tr:nth-child(even) {
            background: #f5f5f5 !important;
        }
        
        tbody td {
            padding: 6px !important;
            border: 1px solid #ccc !important;
            color: #000 !important;
            font-size: 9pt !important;
            line-height: 1.4 !important;
            vertical-align: middle !important;
        }
        
        /* Clean up badges - show only text */
        tbody td span {
            background: none !important;
            color: #000 !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            border-radius: 0 !important;
            font-size: 9pt !important;
            font-weight: normal !important;
            display: inline !important;
        }
        
        /* Remove emojis completely */
        tbody td span::before,
        tbody td span::after {
            content: none !important;
            display: none !important;
        }
        
        /* Clean method text */
        tbody td:nth-child(6) span {
            font-size: 8pt !important;
        }
        
        /* ===== FOOTER ===== */
        .animate-fade::after {
            content: "Dicetak pada: " attr(data-print-date) !important;
            display: block !important;
            text-align: right !important;
            margin-top: 1rem !important;
            padding-top: 0.5rem !important;
            border-top: 1px solid #999 !important;
            font-size: 8pt !important;
            color: #666 !important;
            page-break-before: avoid !important;
        }
        
        /* ===== FORCE COLORS ===== */
        thead, 
        thead *, 
        thead tr, 
        thead th {
            background: #2c2c2c !important;
            color: white !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        tbody tr:nth-child(even), 
        tbody tr:nth-child(even) td {
            background: #f5f5f5 !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        tbody tr:nth-child(odd), 
        tbody tr:nth-child(odd) td {
            background: white !important;
            color: #000 !important;
        }
        
        /* ===== PAGE BREAKS ===== */
        h1, 
        h2, 
        h3, 
        .filter-summary {
            page-break-after: avoid !important;
        }
        
        table {
            page-break-before: auto !important;
        }
        
        thead {
            page-break-after: avoid !important;
        }
        
        tbody tr {
            page-break-inside: avoid !important;
        }
        
        /* ===== REMOVE ALL DECORATIVE ELEMENTS ===== */
        .card {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
        }
        
        /* Ensure no gradients or colors leak through */
        * {
            box-shadow: none !important;
            text-shadow: none !important;
        }
    }
    
    /* Filter summary - hidden on screen, shown on print */
    .filter-summary {
        display: none;
    }
</style>
@endpush
@endsection
