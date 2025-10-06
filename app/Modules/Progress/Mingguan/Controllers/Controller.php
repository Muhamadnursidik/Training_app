<?php
namespace App\Modules\Progress\Mingguan\Controllers;

use App\Bases\BaseModule;
use App\Modules\Progress\Mingguan\Models\Model;
use App\Modules\Progress\Mingguan\Repositories\Repository;
use App\Modules\Progress\Mingguan\Services\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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
        $this->module  = 'progress.mingguan';
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
        $result = $this->repo->startProcess('store', $request);
        return $this->serveJSON($result);
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

            $query = Model::query();

            if ($request->filled('kode_project')) {
                $query->where('kode_project', $request->kode_project);
            }

            if ($request->filled('minggu_ke')) {
                $query->where('minggu_ke', $request->minggu_ke);
            }

            if ($request->filled('aktivitas')) {
                $query->where('aktivitas', 'like', '%' . $request->aktivitas . '%');
            }

            $data = $query->orderBy('tanggal_realisasi', 'desc')->get();

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
        $pdf      = PDF::loadView('exports.realisasi_rencana_pdf', compact('data'));
        $filename = 'realisasi-rencana-mingguan-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        return $pdf->download($filename);
    }

    private function exportWord($data)
    {
        $phpWord  = new \PhpOffice\PhpWord\PhpWord();
        $section  = $phpWord->addSection();
        $table    = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $fontStyle = ['bold' => true];

        // Header
        $table->addRow();
        $headers = ['Kode Project', 'Aktivitas', 'Minggu Ke', 'Realisasi', 'Tanggal Realisasi'];
        foreach ($headers as $header) {
            $table->addCell(2000)->addText($header, $fontStyle);
        }

        // Data
        foreach ($data as $item) {
            $table->addRow();
            $table->addCell(2000)->addText($item->kode_project);
            $table->addCell(2000)->addText($item->aktivitas);
            $table->addCell(2000)->addText($item->minggu_ke);
            $table->addCell(2000)->addText($item->realisasi);
            $table->addCell(2000)->addText($item->tanggal_realisasi ? $item->tanggal_realisasi->format('d/m/Y') : '-');
        }

        $filename = 'realisasi-rencana-mingguan-' . now()->format('Y-m-d-H-i-s') . '.docx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save('php://output');
        exit;
    }

    private function exportExcel($data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['Kode Project', 'Aktivitas', 'Minggu Ke', 'Realisasi', 'Tanggal Realisasi'];
        $col     = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Data
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item->kode_project);
            $sheet->setCellValue('B' . $row, $item->aktivitas);
            $sheet->setCellValue('C' . $row, $item->minggu_ke);
            $sheet->setCellValue('D' . $row, $item->realisasi);
            $sheet->setCellValue('E' . $row, $item->tanggal_realisasi ? $item->tanggal_realisasi->format('d/m/Y') : '-');
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'realisasi-rencana-mingguan-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
