<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile | {{ $user->fullname ?? $user->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass: rgba(255, 255, 255, 0.95);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .profile-card {
            background: var(--glass);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-header {
            background: var(--primary);
            color: white;
            padding: 40px 20px;
            position: relative;
        }

        .avatar-container {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            border: 5px solid rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .avatar-container i {
            font-size: 60px;
            color: var(--primary);
        }

        .profile-body {
            padding: 40px 30px;
        }

        .name {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .position {
            font-size: 16px;
            color: #6366f1;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 25px;
        }

        .info-grid {
            text-align: left;
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 600;
        }

        .info-value {
            font-size: 15px;
            color: #334155;
            font-weight: 500;
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            background: #dcfce7;
            color: #166534;
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .verified-badge i {
            margin-right: 5px;
        }

        .footer {
            padding: 20px;
            border-top: 1px solid #f1f5f9;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="profile-card">
        <div class="profile-header">
            <div class="avatar-container">
                <i class="bi bi-person-fill"></i>
            </div>
        </div>
        <div class="profile-body">
            <div class="verified-badge">
                <i class="bi bi-patch-check-fill"></i> Verified Employee
            </div>
            <div class="name" style="margin-top: 15px;">{{ $user->fullname ?? $user->name }}</div>
            <div class="position">{{ $user->jabatan ?? 'Pegawai BPS Jambi' }}</div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">NIP</div>
                    <div class="info-value">{{ $user->nip_baru ?? $user->nip_lama }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Satuan Kerja</div>
                    <div class="info-value">BPS Jambi</div>
                </div>
            </div>

            <div style="font-size: 14px; color: #64748b;">
                <i class="bi bi-clock-history"></i> Active Profile
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BPS Provinsi Jambi - Presence System
        </div>
    </div>
</body>
</html>
