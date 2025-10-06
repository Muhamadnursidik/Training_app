<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penyesuaian Data Project</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: center;
        }
        th {
            background: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="title">Laporan Penyesuaian Data Project</div>
    <div class="subtitle">Export: {{ now()->format('d/m/Y H:i:s') }}</div>

    <table>
        <thead>
            <tr>
                <th>Kode Project</th>
                <th>Kode Addendum</th>
                <th>Aktivitas</th>
                <th>Level</th>
                <th>Parent Aktivitas</th>
                <th>Bobot (%)</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Akhir</th>
                <th>Minggu Ke-</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->kode_project }}</td>
                <td>{{ $item->kode_addendum }}</td>
                <td style="text-align: left;">{{ $item->aktivitas }}</td>
                <td>{{ $item->level }}</td>
                <td style="text-align: left;">{{ $item->parent ? $item->parent->aktivitas : '-' }}</td>
                <td>{{ $item->bobot }}</td>
                <td>{{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->tanggal_akhir ? $item->tanggal_akhir->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->minggu_ke ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>

    <div class="footer">
        Dicetak oleh Sistem Penyesuaian Data Project<br>
        {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>
</body>
</html>
