<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelacakan Berkas Layanan - MPP Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-gradient-start: #e0e7ff;
            --bg-gradient-end: #f5f3ff;
            --card-bg: rgba(255, 255, 255, 0.85);
            --text-main: #1e1b4b;
            --text-muted: #4b5563;
            --border-color: #e5e7eb;
            
            /* Status Colors */
            --submitted: #6b7280;
            --in_process: #0ea5e9;
            --hold: #ef4444;
            --ready: #f59e0b;
            --completed: #10b981;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--bg-gradient-start), var(--bg-gradient-end));
            min-height: 100vh;
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 650px;
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.08);
            padding: 40px;
            transition: all 0.3s ease;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .header p {
            font-size: 14px;
            color: var(--text-muted);
        }

        .search-form {
            display: flex;
            gap: 12px;
            margin-bottom: 30px;
        }

        .input-group {
            flex-grow: 1;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid var(--border-color);
            border-radius: 14px;
            font-size: 15px;
            outline: none;
            transition: all 0.2s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .input-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .btn {
            padding: 16px 28px;
            background: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* Result Section */
        .result-card {
            border-top: 1px solid var(--border-color);
            padding-top: 30px;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .meta-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .meta-val {
            font-size: 15px;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #fff;
            width: fit-content;
        }

        .status-submitted { background-color: var(--submitted); }
        .status-in_process { background-color: var(--in_process); }
        .status-hold { background-color: var(--hold); }
        .status-ready { background-color: var(--ready); }
        .status-completed { background-color: var(--completed); }

        /* Timeline styles */
        .timeline-section h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .timeline {
            position: relative;
            padding-left: 24px;
            border-left: 2px solid var(--border-color);
            margin-left: 10px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .timeline-item {
            position: relative;
        }

        .timeline-marker {
            position: absolute;
            left: -33px;
            top: 2px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #fff;
            border: 3px solid var(--primary);
            transition: all 0.2s ease;
        }

        .timeline-item.active .timeline-marker {
            background: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
        }

        .timeline-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .timeline-title {
            font-size: 14px;
            font-weight: 700;
        }

        .timeline-date {
            font-size: 12px;
            color: var(--text-muted);
        }

        .timeline-desc {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
            background: #fff;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        /* Error/Alert box */
        .alert {
            padding: 16px 20px;
            border-radius: 14px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #dc2626;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>MPP Digital Tracking</h1>
            <p>Masukkan Nomor Lacak Berkas/Permohonan Anda untuk melihat progress</p>
        </div>

        <form action="{{ route('tracking.search') }}" method="GET" class="search-form">
            <div class="input-group">
                <input type="text" name="nomor_lacak" placeholder="Contoh: MPP-20260716-0001" value="{{ $nomor_lacak ?? '' }}" required autocomplete="off">
            </div>
            <button type="submit" class="btn">Lacak</button>
        </form>

        @if(isset($searched))
            @if($tracking)
                <div class="result-card">
                    <div class="meta-grid">
                        <div class="meta-item">
                            <span class="meta-label">Nomor Lacak</span>
                            <span class="meta-val" style="color: var(--primary);">{{ $tracking->nomor_lacak }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Pemohon</span>
                            <span class="meta-val">{{ $tracking->nama_pemohon }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Instansi / Dinas</span>
                            <span class="meta-val">{{ $tracking->dinas->nama }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Jenis Layanan</span>
                            <span class="meta-val">{{ $tracking->layanan->nama_layanan }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Status Saat Ini</span>
                            <span class="status-badge status-{{ $tracking->status_sekarang }}">
                                {{ match($tracking->status_sekarang) {
                                    'submitted' => 'Diterima',
                                    'in_process' => 'Diproses',
                                    'hold' => 'Ditangguhkan',
                                    'ready' => 'Siap Diambil',
                                    'completed' => 'Selesai',
                                } }}
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Estimasi Selesai</span>
                            <span class="meta-val">{{ $tracking->tanggal_selesai_estimasi ? $tracking->tanggal_selesai_estimasi->format('d M Y, H:i') : '-' }}</span>
                        </div>
                    </div>

                    <div class="timeline-section">
                        <h3>Riwayat Perkembangan Berkas</h3>
                        <div class="timeline">
                            @foreach($tracking->logs as $index => $log)
                                <div class="timeline-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-title" style="color: var(--{{ $log->status }});">
                                            {{ match($log->status) {
                                                'submitted' => 'Berkas Diterima',
                                                'in_process' => 'Sedang Diproses',
                                                'hold' => 'Ditangguhkan (Berkas Kurang)',
                                                'ready' => 'Siap Diambil',
                                                'completed' => 'Berkas Diserahkan',
                                            } }}
                                        </span>
                                        <span class="timeline-date">{{ $log->created_at->format('d M Y, H:i') }} oleh {{ $log->petugas ? $log->petugas->name : 'Sistem' }}</span>
                                        @if($log->catatan)
                                            <p class="timeline-desc">{{ $log->catatan }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="alert">
                    Maaf, nomor lacak <strong>{{ $nomor_lacak }}</strong> tidak ditemukan dalam sistem. Harap periksa kembali penulisan nomor lacak Anda.
                </div>
            @endif
        @endif

        <div class="footer">
            Sistem Informasi Layanan Publik MPP Digital &copy; 2026. <br>
            Kembali ke <a href="/admin">Panel Admin</a>
        </div>
    </div>

</body>
</html>
