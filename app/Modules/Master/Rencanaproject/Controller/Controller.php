<?php
namespace App\Modules\Master\Rencanaproject\Controller;

use App\Bases\BaseModule;
use App\Modules\Master\Rencanaproject\Models\Model;
use App\Modules\Master\Rencanaproject\Repositories\Repository;
use App\Modules\Master\Rencanaproject\Services\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use Carbon\Carbon;

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
        $parents =Model::select('id', 'aktivitas', 'level')->orderBy('level')->orderBy('aktivitas')->get()->map(function($item) {
            return [
                'id'   => $item->id,
                'level' => $item->level,
                'text' => str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $item->level - 1) . $item->aktivitas
            ];
        })->toArray();
        return $this->serveView(compact('parents'));
    }

    public function store(Request $request)
    {
        try {
            $service = new Service();
            $result  = $service->store($request->all());

            return response()->json([
                'success' => true,
                'data'    => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan form edit
     */
    public function edit($id)
    {
        $data = $this->service->get(['id' => decrypt($id)]);
        $projects = Model::all(); // Ambil dari tabel projects
        $parents = $this->service->getParentOptions($data->id);
        
        return $this->serveView(['data' => $data, 'projects' => $projects, 'parents' => $parents]);
    }

    /**
     * Update data
     */
    public function update(Request $request, $id)
    {
        $request->merge(['id' => decrypt($id)]);
        $result = $this->repo->startProcess('update', $request);
        return $this->serveJSON($result);
    }

    /**
     * Hapus data
     */
    public function destroy(Request $request, $id)
    {
        $request->merge(['id' => decrypt($id)]);
        $result = $this->repo->startProcess('destroy', $request);
        return $this->serveJSON($result);
    }

    /**
     * Hapus multiple data
     */
    public function destroys(Request $request)
    {
        $result = $this->repo->startProcess('destroys', $request);
        return $this->serveJSON($result);
    }

    /**
     * Restore data yang telah dihapus
     */
    public function restore(Request $request, $id)
    {
        $request->merge(['id' => decrypt($id)]);
        $result = $this->repo->startProcess('restore', $request);
        return $this->serveJSON($result);
    }

    /**
     * Export data ke berbagai format
     */
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

    /**
     * Halaman import
     */
    public function import()
    {
        return $this->serveView();
    }

    /**
     * Proses import dari file Excel
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $reader = new XlsxReader();
            $spreadsheet = $reader->load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            array_shift($rows);

            $imported = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                try {
                    if (empty($row[1]) || empty($row[2])) continue; // Skip empty rows

                    $data = [
                        'kode_project' => $row[1],
                        'aktivitas' => $row[2],
                        'level' => $row[3] ?? 1,
                        'bobot' => $row[4] ?? 0,
                        'tanggal_mulai' => $row[5] ? Carbon::parse($row[5])->format('Y-m-d') : null,
                        'tanggal_akhir' => $row[6] ? Carbon::parse($row[6])->format('Y-m-d') : null,
                        'minggu_ke' => $row[7] ?? null,
                    ];

                    $this->service->store($data);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $result = [
                'status' => 'success',
                'message' => "Berhasil import {$imported} data",
                'imported' => $imported,
                'errors' => $errors
            ];

            return $this->serveJSON($result);
        } catch (\Exception $e) {
            return $this->serveJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Generate dropdown aktivitas untuk select2
     */
    public function generatedropdownaktivitas(Request $request)
    {
        $search = $request->get('search');
        $data = Model::select('id', 'aktivitas as text')
                    ->when($search, function($query, $search) {
                        return $query->where('aktivitas', 'like', '%' . $search . '%');
                    })
                    ->orderBy('aktivitas')
                    ->limit(20)
                    ->get();

        return response()->json(['results' => $data]);
    }

    /**
     * Generate dropdown parent untuk select2
     */
    public function generatedropdownparent(Request $request)
    {
        $search = $request->get('search');
        $excludeId = $request->get('exclude_id');
        
        $data = Model::select('id', 'aktivitas as text')
                    ->when($search, function($query, $search) {
                        return $query->where('aktivitas', 'like', '%' . $search . '%');
                    })
                    ->when($excludeId, function($query, $excludeId) {
                        return $query->where('id', '!=', $excludeId);
                    })
                    ->orderBy('aktivitas')
                    ->limit(20)
                    ->get();

        return response()->json(['results' => $data]);
    }

    /**
     * Export ke PDF
     */
    private function exportPDF($data, $request = null)
    {
        $pdf = PDF::loadView('exports.rencana-project-pdf', [
            'data' => $data,
            'filters' => $request ? $request->all() : []
        ]);
        
        $pdf->setPaper('A4', 'landscape');
        $filename = 'rencana-project-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export ke Excel
     */
    private function exportExcel($data, $request = null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

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
            'I1' => 'Minggu Ke'
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

        $writer = new Xlsx($spreadsheet);
        $filename = 'rencana-project-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Export ke Word
     */
    private function exportWord($data, $request = null)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection();

        // Title
        $section->addText('Laporan Rencana Project', 
            ['bold' => true, 'size' => 16], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );
        $section->addText('Laporan Lengkap Data Rencana Project', 
            ['size' => 12, 'color' => '666666'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );
        $section->addTextBreak(1);

        // Info
        $section->addText('Tanggal Export: ' . date('d/m/Y H:i:s'), ['size' => 10, 'color' => '666666']);
        $section->addText('Total Data: ' . $data->count() . ' records', ['size' => 10, 'color' => '666666']);
        $section->addTextBreak(1);

        // Table
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
        ];
        $firstRowStyle = ['bgColor' => 'DDDDDD'];
        $phpWord->addTableStyle('DataTable', $tableStyle, $firstRowStyle);

        $table = $section->addTable('DataTable');
        
        // Header
        $table->addRow();
        $table->addCell(800)->addText('No', ['bold' => true]);
        $table->addCell(2000)->addText('Kode Project', ['bold' => true]);
        $table->addCell(3000)->addText('Aktivitas', ['bold' => true]);
        $table->addCell(1000)->addText('Level', ['bold' => true]);
        $table->addCell(2000)->addText('Parent', ['bold' => true]);
        $table->addCell(1200)->addText('Bobot', ['bold' => true]);
        $table->addCell(2000)->addText('Mulai', ['bold' => true]);
        $table->addCell(2000)->addText('Akhir', ['bold' => true]);
        $table->addCell(1200)->addText('Minggu', ['bold' => true]);

        // Data
        foreach ($data as $index => $item) {
            $table->addRow();
            $table->addCell(800)->addText($index + 1);
            $table->addCell(2000)->addText($item->kode_project);
            $table->addCell(3000)->addText($item->aktivitas);
            $table->addCell(1000)->addText($item->level);
            $table->addCell(2000)->addText($item->parent ? $item->parent->aktivitas : '-');
            $table->addCell(1200)->addText($item->bobot . '%');
            $table->addCell(2000)->addText($item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-');
            $table->addCell(2000)->addText($item->tanggal_akhir ? $item->tanggal_akhir->format('d/m/Y') : '-');
            $table->addCell(1200)->addText($item->minggu_ke ?: '-');
        }

        $filename = 'rencana-project-' . date('Y-m-d-H-i-s') . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}