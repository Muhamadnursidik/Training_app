<?php
namespace App\Modules\Master\Dataliburnasional;

use App\Bases\BaseModule;
use App\Modules\Master\Dataliburnasional\Repository;
use App\Modules\Master\Dataliburnasional\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Controller extends BaseModule
{
    private $repo;
    protected $service;

    public function __construct(Repository $repo)
    {
        $this->repo    = $repo;
        $this->service = new Service();
        $this->module  = 'master.dataliburnasional';
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
        return $this->serveView();
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

    public function edit($id)
    {
        $data = Service::get(decrypt($id));
        return $this->serveView([
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate request
            $request->validate([
                'tanggal'    => 'required|date',
                'keterangan' => 'required|string|max:1000',
            ]);

            // Prepare data
            $data = [
                'id'         => $id,
                'tanggal'    => $request->tanggal,
                'keterangan' => $request->keterangan,
            ];

            // Update data
            $result = $this->service->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data'    => $result,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Update field specific
    public function updateField(Request $request, $id)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            $result = $this->service->updateField($id, $field, $value);

            return response()->json([
                'success' => true,
                'message' => 'Field berhasil diupdate',
                'data'    => $result,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Batch update
    public function batchUpdate(Request $request)
    {
        try {
            $dataArray = $request->input('data', []);
            $result    = $this->service->batchUpdate($dataArray);

            return response()->json([
                'success' => true,
                'message' => "{$result} data berhasil diupdate",
                'updated_count' => $result,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

public function destroy($id)
{
    try {
        $service = new Service();
        $result = $service->destroy(['id' => $id]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
            'data'    => $result
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
    // public function destroys(Request $request)
    // {
    //     $result = $this->repo->startProcess('destroys', $request);
    //     return $this->serveJSON($result);
    // }

    public function restore($id)
    {
        try {
            $service = new Service();
            $result = $service->restore(['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil direstore',
                'data'    => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request, $type = null)
    {
        try {
            // default format = excel
            $format = $type ?? $request->input('format', 'excel');

            $query = Model::query();

            if ($request->filled('tanggal')) {
                $query->whereDate('tanggal', $request->tanggal);
            }

            if ($request->filled('keterangan')) {
                $query->where('keterangan', 'like', '%' . $request->keterangan . '%');
            }

            $data = $query->orderBy('tanggal', 'desc')->get();

            switch ($format) {
                case 'pdf':
                    return $this->exportPDF($data);

                case 'word':
                    return $this->exportWord($data);

                case 'excel':
                default:
                    return $this->exportExcel($data);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Export gagal: ' . $e->getMessage()], 500);
        }
    }

    private function exportPDF($data)
    {
        $pdf      = PDF::loadView('exports.pdf', compact('data'));
        $filename = 'data-libur-nasional-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        return $pdf->download($filename);
    }

    private function exportExcel($data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Keterangan');

        $row = 2;
        foreach ($data as $i => $item) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y'));
            $sheet->setCellValue('C' . $row, $item->keterangan);
            $row++;
        }

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer   = new Xlsx($spreadsheet);
        $filename = 'data-libur-nasional-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    private function exportWord($data)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Global style
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection();

        // Header
        $section->addText(
            'Data Libur Nasional',
            ['bold' => true, 'size' => 16],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );
        $section->addText(
            'Laporan Lengkap',
            ['size' => 12, 'color' => '666666'],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );
        $section->addTextBreak(1);

        // Info
        $section->addText('Tanggal Export: ' . date('d/m/Y H:i:s'), ['size' => 10, 'color' => '666666']);
        $section->addText('Total Data: ' . $data->count() . ' records', ['size' => 10, 'color' => '666666']);
        $section->addTextBreak(1);

        // Table style
        $tableStyle = [
            'borderSize'  => 6,
            'borderColor' => '999999',
            'cellMargin'  => 80,
            'alignment'   => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
        ];
        $firstRowStyle = ['bgColor' => 'DDDDDD'];
        $phpWord->addTableStyle('Table', $tableStyle, $firstRowStyle);

        // Table
        $table = $section->addTable('Table');
        $table->addRow();
        $table->addCell(800)->addText('No', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(2000)->addText('Tanggal', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(6000)->addText('Keterangan', ['bold' => true], ['alignment' => 'center']);

        if ($data->count() > 0) {
            foreach ($data as $index => $item) {
                $table->addRow();
                $table->addCell(800)->addText($index + 1, [], ['alignment' => 'center']);
                $table->addCell(2000)->addText(
                    \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y'),
                    [],
                    ['alignment' => 'center']
                );
                $table->addCell(6000)->addText($item->keterangan);
            }
        } else {
            $table->addRow();
            $table->addCell(8800, ['gridSpan' => 3])->addText(
                'Tidak ada data untuk ditampilkan',
                ['italic' => true, 'color' => '999999'],
                ['alignment' => 'center']
            );
        }

        // Footer info
        $section->addTextBreak(2);
        $footerTable = $section->addTable(['borderSize' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);
        $footerTable->addRow();
        $footerTable->addCell(4500)->addText(
            "Generated by: Your Application Name\nSystem: Data Management System",
            ['size' => 9, 'color' => '666666']
        );
        $footerTable->addCell(4500)->addText(
            "Export Date: " . date('d F Y') . "\nTime: " . date('H:i:s') . " WIB",
            ['size' => 9, 'color' => '666666'],
            ['alignment' => 'right']
        );

        // Output file
        $filename = 'data-libur-nasional-' . date('Y-m-d-H-i-s') . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

}
