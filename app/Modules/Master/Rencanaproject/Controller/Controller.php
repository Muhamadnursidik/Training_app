<?php
namespace App\Modules\Master\Rencanaproject\Controller;

use App\Bases\BaseModule;
use App\Modules\Master\Rencanaproject\Models\Model;
use App\Modules\Master\Rencanaproject\Repositories\Repository;
use App\Modules\Master\Rencanaproject\Services\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Controller extends BaseModule
{
    private $repo;
    protected $service;

    public function __construct(Repository $repo, Service $service)
    {
        $this->repo    = $repo;
        $this->service = $service;  
        $this->module  = 'master.rencanaproject';
        parent::__construct();
    }

    public function index()
    {
        activity('Akses menu')->log('Akses menu ' . $this->pageTitle);
        return $this->serveView();
    }

    public function data(Request $request)
    {
        $result = $this->repo->startProcess('data', $request);
        return $this->serveJSON($result);
    }

    public function create()
    {
        $parents = Model::select('id', 'aktivitas', 'level')->orderBy('level')->orderBy('aktivitas')->get()->map(function ($item) {
            return [
                'id'    => $item->id,
                'level' => $item->level,
                'text'  => str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $item->level - 1) . $item->aktivitas,
            ];
        })->toArray();
        return $this->serveView(compact('parents'));
    }

    public function store(Request $request)
    {
        try {
            $service = new Service();
            $result  = $service->store($request->all());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil disimpan',
                    'data'    => $result,
                ]);
            }

            return redirect()
                ->route($this->module . '.index')
                ->with('success', 'Data berhasil disimpan');

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $data = $this->service->get(decrypt($id));
        return $this->serveView([
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->merge(['id' => decrypt($id)]);
        $result = $this->repo->startProcess('update', $request);
        return $this->serveJSON($result);
    }

    public function destroy(Request $request, $id)
    {
        $request->merge(['id' => decrypt($id)]);
        $result = $this->repo->startProcess('destroy', $request);
        return $this->serveJSON($result);
    }

    public function destroys(Request $request)
    {
        $result = $this->repo->startProcess('destroys', $request);
        return $this->serveJSON($result);
    }

    public function restore(Request $request, $id)
    {
        $request->merge(['id' => decrypt($id)]);
        $result = $this->repo->startProcess('restore', $request);
        return $this->serveJSON($result);
    }

    public function export(Request $request, $type = null)
    {
        try {
            $format = $type ?? $request->input('format', 'excel');

            $query = Model::query()->with('parent');

            // Apply filters
            if ($request->filled('kode_project')) {
                $query->where('kode_project', $request->kode_project);
            }

            if ($request->filled('aktivitas')) {
                $query->where('aktivitas', 'like', '%' . $request->aktivitas . '%');
            }

            if ($request->filled('minggu_ke')) {
                $query->where('minggu_ke', $request->minggu_ke);
            }

            if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
                $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_akhir]);
            }

            $data = $query->orderBy('kode_project')->orderBy('level')->orderBy('tanggal_mulai')->get();

            switch ($format) {
                case 'pdf':
                    return $this->exportPDF($data, $request);

                case 'word':
                    return $this->exportWord($data, $request);

                case 'excel':
                default:
                    return $this->exportExcel($data, $request);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Export gagal: ' . $e->getMessage()], 500);
        }
    }

    public function import()
    {
        return $this->serveView();
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            $file        = $request->file('file');
            $reader      = new XlsxReader();
            $spreadsheet = $reader->load($file->getPathname());
            $worksheet   = $spreadsheet->getActiveSheet();
            $rows        = $worksheet->toArray();

            // Skip header row
            array_shift($rows);

            $imported = 0;
            $errors   = [];

            foreach ($rows as $index => $row) {
                try {
                    if (empty($row[1]) || empty($row[2])) {
                        continue;
                    }
                    // Skip empty rows

                    $data = [
                        'kode_project'  => $row[1],
                        'aktivitas'     => $row[2],
                        'level'         => $row[3] ?? 1,
                        'bobot'         => $row[4] ?? 0,
                        'tanggal_mulai' => $row[5] ? Carbon::parse($row[5])->format('Y-m-d') : null,
                        'tanggal_akhir' => $row[6] ? Carbon::parse($row[6])->format('Y-m-d') : null,
                        'minggu_ke'     => $row[7] ?? null,
                    ];

                    $this->service->store($data);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $result = [
                'status'  => 'success',
                'message' => "Berhasil import {$imported} data",
                'imported' => $imported,
                'errors'   => $errors,
            ];

            return $this->serveJSON($result);
        } catch (\Exception $e) {
            return $this->serveJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function generatedropdownaktivitas(Request $request)
    {
        $search = $request->get('search');
        $data   = Model::select('id', 'aktivitas as text')
            ->when($search, function ($query, $search) {
                return $query->where('aktivitas', 'like', '%' . $search . '%');
            })
            ->orderBy('aktivitas')
            ->limit(20)
            ->get();

        return response()->json(['results' => $data]);
    }

    public function generatedropdownparent(Request $request)
    {
        $search    = $request->get('search');
        $excludeId = $request->get('exclude_id');

        $data = Model::select('id', 'aktivitas as text')
            ->when($search, function ($query, $search) {
                return $query->where('aktivitas', 'like', '%' . $search . '%');
            })
            ->when($excludeId, function ($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->orderBy('aktivitas')
            ->limit(20)
            ->get();

        return response()->json(['results' => $data]);
    }

    private function exportPDF($data, $request = null)
    {
        $pdf = PDF::loadView('exports.rencana-project-pdf', [
            'data'    => $data,
            'filters' => $request ? $request->all() : [],
        ]);

        $pdf->setPaper('A4', 'landscape');
        $filename = 'rencana-project-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    private function exportExcel($data, $request = null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Set header
        $headers = [
            'A1' => 'No',
            'B1' => 'Kode Project',
            'C1' => 'Aktivitas',
            'D1' => 'Level',
            'E1' => 'Parent',
            'F1' => 'Bobot (%)',
            'G1' => 'Tanggal Mulai',
            'H1' => 'Tanggal Akhir',
            'I1' => 'Minggu Ke',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Fill data
        $row = 2;
        foreach ($data as $i => $item) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $item->kode_project);
            $sheet->setCellValue('C' . $row, $item->aktivitas);
            $sheet->setCellValue('D' . $row, $item->level);
            $sheet->setCellValue('E' . $row, $item->parent ? $item->parent->aktivitas : '-');
            $sheet->setCellValue('F' . $row, $item->bobot);
            $sheet->setCellValue('G' . $row, $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-');
            $sheet->setCellValue('H' . $row, $item->tanggal_akhir ? $item->tanggal_akhir->format('d/m/Y') : '-');
            $sheet->setCellValue('I' . $row, $item->minggu_ke ?: '-');
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer   = new Xlsx($spreadsheet);
        $filename = 'rencana-project-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    private function exportWord($data, $request = null)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Set default font dan style
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(11);

        // Add section dengan margin
        $sectionStyle = [
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2),
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2),
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2),
            'orientation' => 'landscape'
        ];
        $section = $phpWord->addSection($sectionStyle);

        // Header dengan logo dan info perusahaan
        $header = $section->addHeader();
        $headerTable = $header->addTable(['borderSize' => 0, 'cellMargin' => 50]);
        $headerTable->addRow();
        $headerTable->addCell(4000)->addText('PT. NAMA PERUSAHAAN', ['bold' => true, 'size' => 12], ['alignment' => 'left']);
        $headerTable->addCell(4000)->addText('Sistem Informasi Manajemen Project', ['size' => 10], ['alignment' => 'center']);
        $headerTable->addCell(4000)->addText('Tanggal: ' . now()->format('d/m/Y'), ['size' => 10], ['alignment' => 'right']);

        // Title
        $section->addText('LAPORAN RENCANA PROJECT', 
            ['bold' => true, 'size' => 18, 'color' => '2c3e50'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 240]
        );

        // Info dan statistik
        $infoTable = $section->addTable(['borderSize' => 6, 'borderColor' => 'cccccc', 'cellMargin' => 80]);
        $infoTable->addRow();
        $infoTable->addCell(2500)->addText('Tanggal Export:', ['bold' => true]);
        $infoTable->addCell(2500)->addText(now()->format('d/m/Y H:i:s'));
        $infoTable->addCell(2500)->addText('Total Data:', ['bold' => true]);
        $infoTable->addCell(2500)->addText($data->count() . ' aktivitas');

        $infoTable->addRow();
        $infoTable->addCell(2500)->addText('User Export:', ['bold' => true]);
        $infoTable->addCell(2500)->addText(auth()->user()->name ?? 'System');
        $infoTable->addCell(2500)->addText('Total Project:', ['bold' => true]);
        $infoTable->addCell(2500)->addText($data->unique('kode_project')->count() . ' project');

        $section->addTextBreak(1);

        // Filter information
        if (isset($request) && !empty(array_filter($request->all()))) {
            $section->addText('Filter yang Diterapkan:', ['bold' => true, 'size' => 12, 'color' => '2c3e50']);
            $filterTable = $section->addTable(['borderSize' => 3, 'borderColor' => 'e0e0e0', 'cellMargin' => 60]);
            
            foreach ($request->all() as $key => $value) {
                if (!empty($value) && !in_array($key, ['_token', '_method'])) {
                    $filterTable->addRow();
                    $filterTable->addCell(2000)->addText(ucfirst(str_replace('_', ' ', $key)) . ':', ['bold' => true]);
                    $filterTable->addCell(6000)->addText($value);
                }
            }
            $section->addTextBreak(1);
        }

        // Statistik ringkasan
        $stats = [
            'Total Aktivitas' => $data->count(),
            'Total Project' => $data->unique('kode_project')->count(),
            'Root Activities' => $data->where('level', 1)->count(),
            'Selesai' => $data->where('tanggal_akhir', '<', now()->format('Y-m-d'))->count(),
            'Berjalan' => $data->where('tanggal_mulai', '<=', now()->format('Y-m-d'))
                              ->where('tanggal_akhir', '>=', now()->format('Y-m-d'))->count(),
        ];

        $section->addText('Statistik Ringkasan:', ['bold' => true, 'size' => 12, 'color' => '2c3e50']);
        $statsTable = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '2c3e50',
            'cellMargin' => 80,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
        ]);
        
        // Header statistik
        $statsTable->addRow(400);
        foreach ($stats as $label => $value) {
            $statsTable->addCell(2000, ['bgColor' => '34495e'])
                      ->addText($label, ['bold' => true, 'color' => 'ffffff', 'size' => 10], ['alignment' => 'center']);
        }
        
        // Data statistik
        $statsTable->addRow(600);
        foreach ($stats as $label => $value) {
            $statsTable->addCell(2000, ['bgColor' => 'ecf0f1'])
                      ->addText($value, ['bold' => true, 'size' => 14, 'color' => '2c3e50'], ['alignment' => 'center']);
        }

        $section->addTextBreak(1);

        // Group data by project
        $groupedData = $data->groupBy('kode_project');
        $loop = collect($groupedData)->keys();

        foreach ($groupedData as $kodeProject => $projectData) {
            if (!$loop->first) {
                $section->addPageBreak();
            }

            // Project header
            $section->addText('PROJECT: ' . $kodeProject, 
                ['bold' => true, 'size' => 14, 'color' => 'ffffff'], 
                ['alignment' => 'left', 'bgColor' => '2c3e50', 'spaceAfter' => 120, 'spaceBefore' => 120]
            );

            // Project summary
            $projectSummary = $section->addTable(['borderSize' => 3, 'borderColor' => '3498db', 'cellMargin' => 60]);
            $projectSummary->addRow();
            $projectSummary->addCell(2000)->addText('Total Aktivitas:', ['bold' => true]);
            $projectSummary->addCell(2000)->addText($projectData->count());
            $projectSummary->addCell(2000)->addText('Total Bobot:', ['bold' => true]);
            $projectSummary->addCell(2000)->addText(number_format($projectData->sum('bobot'), 2) . '%');

            $section->addTextBreak(1);

            // Data table
            $tableStyle = [
                'borderSize' => 6,
                'borderColor' => '999999',
                'cellMargin' => 80,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            ];
            $firstRowStyle = ['bgColor' => '34495e'];
            $phpWord->addTableStyle('ProjectTable', $tableStyle, $firstRowStyle);

            $table = $section->addTable('ProjectTable');
            
            // Header table
            $table->addRow(500);
            $table->addCell(400)->addText('No', ['bold' => true, 'color' => 'ffffff', 'size' => 9], ['alignment' => 'center']);
            $table->addCell(3000)->addText('Aktivitas', ['bold' => true, 'color' => 'ffffff', 'size' => 9]);
            $table->addCell(600)->addText('Level', ['bold' => true, 'color' => 'ffffff', 'size' => 9], ['alignment' => 'center']);
            $table->addCell(2000)->addText('Parent', ['bold' => true, 'color' => 'ffffff', 'size' => 9]);
            $table->addCell(800)->addText('Bobot', ['bold' => true, 'color' => 'ffffff', 'size' => 9], ['alignment' => 'center']);
            $table->addCell(1200)->addText('Mulai', ['bold' => true, 'color' => 'ffffff', 'size' => 9], ['alignment' => 'center']);
            $table->addCell(1200)->addText('Akhir', ['bold' => true, 'color' => 'ffffff', 'size' => 9], ['alignment' => 'center']);
            $table->addCell(500)->addText('Minggu', ['bold' => true, 'color' => 'ffffff', 'size' => 9], ['alignment' => 'center']);
            $table->addCell(800)->addText('Status', ['bold' => true, 'color' => 'ffffff', 'size' => 9], ['alignment' => 'center']);

            // Data rows
            foreach ($projectData->sortBy(['level', 'tanggal_mulai']) as $index => $item) {
                // Determine status
                $today = now()->format('Y-m-d');
                $startDate = $item->tanggal_mulai ? $item->tanggal_mulai->format('Y-m-d') : null;
                $endDate = $item->tanggal_akhir ? $item->tanggal_akhir->format('Y-m-d') : null;
                
                $status = 'Akan Datang';
                $statusColor = 'f39c12';
                
                if ($endDate && $endDate < $today) {
                    $status = 'Selesai';
                    $statusColor = '95a5a6';
                } elseif ($startDate && $startDate <= $today && $endDate && $endDate >= $today) {
                    $status = 'Berjalan';
                    $statusColor = '2ecc71';
                }

                // Row style (alternate colors)
                $rowStyle = $index % 2 == 0 ? ['bgColor' => 'f8f9fa'] : [];
                
                $table->addRow(300, $rowStyle);
                $table->addCell(400)->addText($index + 1, ['size' => 9], ['alignment' => 'center']);
                
                // Aktivitas dengan indentasi
                $indent = str_repeat('    ', max(0, $item->level - 1));
                $activityText = $indent . $item->aktivitas;
                $table->addCell(3000)->addText($activityText, ['size' => 9]);
                
                $table->addCell(600)->addText($item->level, ['size' => 9, 'bold' => true], ['alignment' => 'center']);
                $table->addCell(2000)->addText($item->parent ? $item->parent->aktivitas : '-', ['size' => 9]);
                $table->addCell(800)->addText(number_format($item->bobot, 2) . '%', ['size' => 9], ['alignment' => 'right']);
                $table->addCell(1200)->addText($item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-', ['size' => 9], ['alignment' => 'center']);
                $table->addCell(1200)->addText($item->tanggal_akhir ? $item->tanggal_akhir->format('d/m/Y') : '-', ['size' => 9], ['alignment' => 'center']);
                $table->addCell(500)->addText($item->minggu_ke ?: '-', ['size' => 9], ['alignment' => 'center']);
                $table->addCell(800)->addText($status, ['size' => 9, 'color' => $statusColor, 'bold' => true], ['alignment' => 'center']);
            }

            $section->addTextBreak(1);
        }

        // Footer
        $footer = $section->addFooter();
        $footerTable = $footer->addTable(['borderSize' => 0]);
        $footerTable->addRow();
        $footerTable->addCell(4000)->addText('Laporan Rencana Project - ' . now()->format('d F Y'), ['size' => 9, 'color' => '7f8c8d']);
        $footerTable->addCell(4000)->addText('Halaman {PAGE} dari {NUMPAGES}', ['size' => 9, 'color' => '7f8c8d'], ['alignment' => 'center']);
        $footerTable->addCell(4000)->addText('Confidential', ['size' => 9, 'color' => '7f8c8d'], ['alignment' => 'right']);

        // Save file
        $filename = 'rencana-project-' . now()->format('Y-m-d-H-i-s') . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
