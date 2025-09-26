<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rencana Project</title>
    <style>
        /* Reset dan Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2c3e50;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header h2 {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: normal;
            margin-bottom: 10px;
        }

        .header .company-info {
            font-size: 9px;
            color: #95a5a6;
            line-height: 1.3;
        }

        /* Info Section */
        .info-section {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
        }

        .info-row {
            display: inline-block;
            width: 48%;
            margin-bottom: 5px;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
            display: inline-block;
            width: 120px;
        }

        .info-value {
            color: #555;
        }

        /* Filter Info */
        .filter-info {
            margin-bottom: 15px;
            padding: 8px;
            background-color: #ecf0f1;
            border-radius: 3px;
            font-size: 9px;
        }

        .filter-info h4 {
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 10px;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9px;
        }

        .data-table th {
            background-color: #34495e;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #2c3e50;
            font-size: 9px;
        }

        .data-table td {
            padding: 6px 4px;
            border: 1px solid #bdc3c7;
            vertical-align: top;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .data-table tbody tr:hover {
            background-color: #e8f4fd;
        }

        /* Level Indentation */
        .level-1 { padding-left: 0px; }
        .level-2 { padding-left: 10px; color: #555; }
        .level-3 { padding-left: 20px; color: #777; }
        .level-4 { padding-left: 30px; color: #999; }
        .level-5 { padding-left: 40px; color: #aaa; }

        /* Badges */
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
            min-width: 40px;
        }

        .badge-level-1 { background-color: #e74c3c; color: white; }
        .badge-level-2 { background-color: #f39c12; color: white; }
        .badge-level-3 { background-color: #f1c40f; color: #333; }
        .badge-level-4 { background-color: #2ecc71; color: white; }
        .badge-level-5 { background-color: #9b59b6; color: white; }

        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: #ecf0f1;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background-color: #3498db;
            border-radius: 4px;
        }

        /* Status Colors */
        .status-upcoming { color: #f39c12; }
        .status-ongoing { color: #2ecc71; font-weight: bold; }
        .status-completed { color: #95a5a6; }
        .status-overdue { color: #e74c3c; font-weight: bold; }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #bdc3c7;
            text-align: center;
            font-size: 8px;
            color: #7f8c8d;
        }

        .footer .signature {
            margin-top: 40px;
            text-align: right;
        }

        .footer .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin: 40px 0 5px auto;
        }

        /* Summary Stats */
        .summary-stats {
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }

        .stat-item {
            display: table-cell;
            width: 20%;
            text-align: center;
            padding: 10px;
            background-color: #ecf0f1;
            border: 1px solid #bdc3c7;
        }

        .stat-number {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            display: block;
        }

        .stat-label {
            font-size: 8px;
            color: #7f8c8d;
            text-transform: uppercase;
        }

        /* Responsive untuk print */
        @media print {
            body { font-size: 9px; }
            .header h1 { font-size: 16px; }
            .header h2 { font-size: 12px; }
            .data-table th,
            .data-table td { font-size: 8px; padding: 4px 2px; }
        }

        /* Project Group Styles */
        .project-group {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .project-header {
            background-color: #2c3e50;
            color: white;
            padding: 8px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 11px;
        }

        .project-summary {
            background-color: #f8f9fa;
            padding: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #3498db;
            font-size: 9px;
        }

        /* Page break */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Laporan Rencana Project</h1>
        <h2>Sistem Informasi Manajemen Project</h2>
        <div class="company-info">
            <div>PT. Nama Perusahaan</div>
            <div>Alamat Perusahaan, Kota, Kode Pos</div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Tanggal Export:</span>
            <span class="info-value">{{ now()->format('d/m/Y H:i:s') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Data:</span>
            <span class="info-value">{{ $data->count() }} aktivitas</span>
        </div>
        <div class="info-row">
            <span class="info-label">User Export:</span>
            <span class="info-value">{{ auth()->user()->name ?? 'System' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Project:</span>
            <span class="info-value">{{ $data->unique('kode_project')->count() }} project</span>
        </div>
    </div>

    <!-- Filter Information -->
    @if(isset($filters) && !empty(array_filter($filters)))
    <div class="filter-info">
        <h4>Filter yang Diterapkan:</h4>
        @if(!empty($filters['kode_project']))
            <div><strong>Kode Project:</strong> {{ $filters['kode_project'] }}</div>
        @endif
        @if(!empty($filters['aktivitas']))
            <div><strong>Aktivitas:</strong> {{ $filters['aktivitas'] }}</div>
        @endif
        @if(!empty($filters['level']))
            <div><strong>Level:</strong> {{ $filters['level'] }}</div>
        @endif
        @if(!empty($filters['tanggal_mulai']) || !empty($filters['tanggal_akhir']))
            <div><strong>Rentang Tanggal:</strong> 
                {{ $filters['tanggal_mulai'] ?? '-' }} s/d {{ $filters['tanggal_akhir'] ?? '-' }}
            </div>
        @endif
        @if(!empty($filters['minggu_ke']))
            <div><strong>Minggu Ke:</strong> {{ $filters['minggu_ke'] }}</div>
        @endif
    </div>
    @endif

    <!-- Summary Statistics -->
    @php
        $stats = [
            'total' => $data->count(),
            'projects' => $data->unique('kode_project')->count(),
            'root_activities' => $data->where('level', 1)->count(),
            'completed' => $data->where('tanggal_akhir', '<', now()->format('Y-m-d'))->count(),
            'ongoing' => $data->where('tanggal_mulai', '<=', now()->format('Y-m-d'))
                           ->where('tanggal_akhir', '>=', now()->format('Y-m-d'))->count(),
        ];
    @endphp

    <div class="summary-stats">
        <div class="stat-item">
            <span class="stat-number">{{ $stats['total'] }}</span>
            <span class="stat-label">Total Aktivitas</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['projects'] }}</span>
            <span class="stat-label">Total Project</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['root_activities'] }}</span>
            <span class="stat-label">Root Activities</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['completed'] }}</span>
            <span class="stat-label">Selesai</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['ongoing'] }}</span>
            <span class="stat-label">Berjalan</span>
        </div>
    </div>

    <!-- Data by Project -->
    @php
        $groupedData = $data->groupBy('kode_project');
    @endphp

    @foreach($groupedData as $kodeProject => $projectData)
        @if(!$loop->first)
            <div class="page-break"></div>
        @endif
        
        <div class="project-group">
            <div class="project-header">
                PROJECT: {{ $kodeProject }}
            </div>
            
            <div class="project-summary">
                <div class="info-row">
                    <span class="info-label">Total Aktivitas:</span>
                    <span class="info-value">{{ $projectData->count() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Bobot:</span>
                    <span class="info-value">{{ number_format($projectData->sum('bobot'), 2) }}%</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Periode:</span>
                    <span class="info-value">
                        {{ $projectData->min('tanggal_mulai') ? \Carbon\Carbon::parse($projectData->min('tanggal_mulai'))->format('d/m/Y') : '-' }}
                        s/d
                        {{ $projectData->max('tanggal_akhir') ? \Carbon\Carbon::parse($projectData->max('tanggal_akhir'))->format('d/m/Y') : '-' }}
                    </span>
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Aktivitas</th>
                        <th width="8%">Level</th>
                        <th width="20%">Parent</th>
                        <th width="8%">Bobot</th>
                        <th width="12%">Mulai</th>
                        <th width="12%">Akhir</th>
                        <th width="5%">Minggu</th>
                        <th width="5%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projectData->sortBy(['level', 'tanggal_mulai']) as $index => $item)
                        @php
                            $today = now()->format('Y-m-d');
                            $startDate = $item->tanggal_mulai ? $item->tanggal_mulai->format('Y-m-d') : null;
                            $endDate = $item->tanggal_akhir ? $item->tanggal_akhir->format('Y-m-d') : null;
                            
                            $status = 'upcoming';
                            $statusText = 'Akan Datang';
                            
                            if ($endDate && $endDate < $today) {
                                $status = 'completed';
                                $statusText = 'Selesai';
                            } elseif ($startDate && $startDate <= $today && $endDate && $endDate >= $today) {
                                $status = 'ongoing';
                                $statusText = 'Berjalan';
                            } elseif ($endDate && $endDate < $today) {
                                $status = 'overdue';
                                $statusText = 'Terlambat';
                            }
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td class="level-{{ min($item->level, 5) }}">
                                {{ str_repeat('• ', max(0, $item->level - 1)) }}{{ $item->aktivitas }}
                            </td>
                            <td style="text-align: center;">
                                <span class="badge badge-level-{{ min($item->level, 5) }}">{{ $item->level }}</span>
                            </td>
                            <td>{{ $item->parent ? $item->parent->aktivitas : '-' }}</td>
                            <td style="text-align: right;">
                                {{ number_format($item->bobot, 2) }}%
                                <div class="progress-bar" style="margin-top: 2px;">
                                    <div class="progress-fill" style="width: {{ min($item->bobot, 100) }}%;"></div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-' }}
                            </td>
                            <td style="text-align: center;">
                                {{ $item->tanggal_akhir ? $item->tanggal_akhir->format('d/m/Y') : '-' }}
                            </td>
                            <td style="text-align: center;">{{ $item->minggu_ke ?: '-' }}</td>
                            <td style="text-align: center;" class="status-{{ $status }}">
                                {{ $statusText }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <!-- Overall Summary Table -->
    @if($groupedData->count() > 1)
        <div class="page-break"></div>
        <div class="project-group">
            <div class="project-header">
                RINGKASAN KESELURUHAN PROJECT
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th width="20%">Kode Project</th>
                        <th width="15%">Total Aktivitas</th>
                        <th width="15%">Total Bobot (%)</th>
                        <th width="15%">Tanggal Mulai</th>
                        <th width="15%">Tanggal Akhir</th>
                        <th width="10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedData as $kodeProject => $projectData)
                        @php
                            $minStart = $projectData->min('tanggal_mulai');
                            $maxEnd = $projectData->max('tanggal_akhir');
                            $totalBobot = $projectData->sum('bobot');
                            
                            $projectStatus = 'upcoming';
                            if ($maxEnd && $maxEnd->format('Y-m-d') < now()->format('Y-m-d')) {
                                $projectStatus = 'completed';
                            } elseif ($minStart && $minStart->format('Y-m-d') <= now()->format('Y-m-d')) {
                                $projectStatus = 'ongoing';
                            }
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                            <td><strong>{{ $kodeProject }}</strong></td>
                            <td style="text-align: center;">{{ $projectData->count() }}</td>
                            <td style="text-align: right;">{{ number_format($totalBobot, 2) }}%</td>
                            <td style="text-align: center;">
                                {{ $minStart ? $minStart->format('d/m/Y') : '-' }}
                            </td>
                            <td style="text-align: center;">
                                {{ $maxEnd ? $maxEnd->format('d/m/Y') : '-' }}
                            </td>
                            <td style="text-align: center;" class="status-{{ $projectStatus }}">
                                @if($projectStatus === 'completed') Selesai
                                @elseif($projectStatus === 'ongoing') Berjalan
                                @else Akan Datang
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>
            <strong>Laporan Rencana Project</strong><br>
            Dicetak pada: {{ now()->format('d F Y, H:i:s') }} WIB<br>
            Total halaman: <script>document.write(window.print ? 'Auto' : '1')</script>
        </div>
        
        <div class="signature">
            <div style="margin-top: 30px;">
                <div>Mengetahui,</div>
                <div class="signature-line"></div>
                <div>Project Manager</div>
            </div>
        </div>
    </div>
</body>
</html>