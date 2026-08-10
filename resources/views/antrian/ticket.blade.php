<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrian</title>
    <style>
        @page {
            size: 56mm 80mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "Courier New", Courier, monospace;
            background: #ffffff;
            color: #000000;
            font-size: 11px;
        }

        .ticket {
            width: 56mm;
            min-height: 80mm;
            box-sizing: border-box;
            padding: 4mm;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #000000;
        }

        .center {
            text-align: center;
        }

        .title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .subtitle {
            font-size: 9px;
            margin-top: 1mm;
        }

        .divider {
            border-top: 1px dashed #000000;
            margin: 3mm 0;
        }

        .queue {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            margin: 3mm 0;
            letter-spacing: 1px;
        }

        .info {
            line-height: 1.5;
            font-size: 10px;
        }

        .label {
            font-weight: bold;
        }

        .footer {
            margin-top: 3mm;
            text-align: center;
            font-size: 8px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="center">
            <div class="title">SISTEM ANTRIAN MPP</div>
            <div class="subtitle">Nomor Antrian Pelayanan</div>
        </div>

        <div class="divider"></div>

        <div class="queue">{{ $antrian->nomor_antrian }}</div>

        <div class="info">
            <div><span class="label">Nama:</span> {{ $antrian->nama_pemohon }}</div>
            <div><span class="label">HP:</span> {{ $antrian->nomor_hp }}</div>
            <div><span class="label">Dinas:</span> {{ $antrian->dinas->nama ?? '-' }}</div>
            <div><span class="label">Layanan:</span> {{ $antrian->layanan->nama_layanan ?? '-' }}</div>
            <div><span class="label">Tanggal:</span> {{ $antrian->tanggal?->translatedFormat('d F Y') }}</div>
            <div><span class="label">Status:</span> {{ strtoupper($antrian->status) }}</div>
        </div>

        <div class="divider"></div>

        <div class="footer">
            Harap datang sesuai jadwal yang ditampilkan di layar antrian.
        </div>
    </div>
</body>
</html>
