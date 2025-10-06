<?php
namespace App\Modules\Master\DataMitra\Services;

use App\Bases\BaseService;
use App\Modules\Master\DataMitra\Models\Datamitra;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use Dompdf\Dompdf;
use Dompdf\Options;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class Service extends BaseService
{
    public function __construct()
    {
    }

    public function data(array $data)
    {
        $query = Datamitra::query(); // Hapus withTrashed()

        return FacadesDataTables::of($query)
            ->filter(function ($query) use ($data) {
                if (isset($data['kode_mitra']) && $data['kode_mitra'] != '') {
                    $query->where('kode_mitra', 'ILIKE', '%' . $data['kode_mitra'] . '%');
                }

                if (isset($data['nama_mitra']) && $data['nama_mitra'] != '') {
                    $query->where('nama_mitra', 'ILIKE', '%' . $data['nama_mitra'] . '%');
                }

                if (isset($data['alamat']) && $data['alamat'] != '') {
                    $query->where('alamat', 'ILIKE', '%' . $data['alamat'] . '%');
                }
            })
            ->addColumn('id', function ($query) {
                return encrypt($query->id);
            })
            ->make(true);
    }

    public function store(array $data)
    {
        return Datamitra::create([
            'kode_mitra' => $data['kode_mitra'],
            'nama_mitra' => $data['nama_mitra'],
            'alamat' => $data['alamat'],
        ]);
    }

    public static function get($id)
    {
        if ($id) {
            $query = Datamitra::find($id);
            if ($query) {
                return $query;
            }
        }

        throw new \Exception('Data dengan ID ' . $id . ' tidak ditemukan');
    }

    public function update(array $data)
    {
        $model = Datamitra::find($data['id']);
        if ($model) {
            return $model->update([
                'kode_mitra' => $data['kode_mitra'],
                'nama_mitra' => $data['nama_mitra'],
                'alamat' => $data['alamat'],
            ]);
        }
        return false;
    }

    public function destroy(array $data)
    {
        $model = Datamitra::find($data['id']);
        if ($model) {
            return $model->forceDelete(); // Hard delete
        }
        return false;
    }

    public function destroys(array $data)
    {
        $id = [];
        foreach ($data['id'] as $value) {
            $id[] = decrypt($value);
        }

        return Datamitra::whereIn('id', $id)->forceDelete(); // Hard delete
    }

    public static function count()
    {
        return Datamitra::count();
    }

    public static function dropdown($default = '')
    {
        $results = [];

        if ($default) {
            $results[''] = __('Pilih');
        }

        $cursors = Datamitra::orderBy('nama_mitra', 'asc')->get();

        foreach ($cursors as $cursor) {
            $results[$cursor->id] = $cursor->nama_mitra;
        }

        return $results;
    }

    public function download(array $data)
    {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('Data Mitra');

        $headers = ['kode_mitra', 'nama_mitra', 'alamat'];

        for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1) . '1', $headers[$i]);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'template_import_data_mitra.xlsx';
        $writer->save(storage_path($filename));

        return response()->download(storage_path($filename))->deleteFileAfterSend();
    }

    public function import(array $data)
    {
        try {
            $file = $data['files']->getRealPath();

            $spreadsheet = IOFactory::load($file);
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();

            for ($i = 2; $i <= $sheet->getHighestRow(); $i++) {
                $input['kode_mitra'] = $sheet->getCell("A$i")->getValue();
                $input['nama_mitra'] = $sheet->getCell("B$i")->getValue();
                $input['alamat'] = $sheet->getCell("C$i")->getValue();

                $results[] = Datamitra::updateOrCreateOne(['kode_mitra' => $input['kode_mitra']], $input);
            }

            return $results;
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'status' => 'error',
                'message' => __('Proses simpan gagal.'),
                'data' => $e->getMessage()
            ];
        }
    }

    public function exportPdf(array $filters = [])
    {
        try {
            $query = Datamitra::query();

            // Apply filters
            if (isset($filters['kode_mitra']) && $filters['kode_mitra'] != '') {
                $query->where('kode_mitra', 'like', '%' . $filters['kode_mitra'] . '%');
            }
            if (isset($filters['nama_mitra']) && $filters['nama_mitra'] != '') {
                $query->where('nama_mitra', 'like', '%' . $filters['nama_mitra'] . '%');
            }
            if (isset($filters['alamat']) && $filters['alamat'] != '') {
                $query->where('alamat', 'like', '%' . $filters['alamat'] . '%');
            }

            $data = $query->orderBy('nama_mitra', 'asc')->get();
            $html = $this->generatePdfHtml($data);

            // Create logs directory if not exists
            $logsDir = storage_path('logs');
            if (!file_exists($logsDir)) {
                mkdir($logsDir, 0755, true);
            }

            // DomPDF v2.0.3 Configuration
            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->setIsRemoteEnabled(true);
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsPhpEnabled(false);
            $options->set('chroot', public_path());
            $options->set('tempDir', storage_path('app/temp'));
            $options->set('logOutputFile', storage_path('logs/dompdf.log'));
            $options->setDefaultPaperSize('A4');
            $options->setDefaultPaperOrientation('portrait');

            // Initialize DomPDF
            $dompdf = new Dompdf($options);

            // Load HTML content
            $dompdf->loadHtml($html);

            // Set paper size and orientation
            $dompdf->setPaper('A4', 'portrait');

            // Render PDF
            $dompdf->render();

            $filename = 'data_mitra_' . date('Y-m-d_H-i-s') . '.pdf';

            return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'private, max-age=0, must-revalidate')
                ->header('Pragma', 'public');

        } catch (\Exception $e) {
            throw new \Exception('Gagal export PDF: ' . $e->getMessage());
        }
    }

    public function exportExcel(array $filters = [])
    {
        try {
            $query = Datamitra::query();

            // Apply filters
            if (isset($filters['kode_mitra']) && $filters['kode_mitra'] != '') {
                $query->where('kode_mitra', 'like', '%' . $filters['kode_mitra'] . '%');
            }
            if (isset($filters['nama_mitra']) && $filters['nama_mitra'] != '') {
                $query->where('nama_mitra', 'like', '%' . $filters['nama_mitra'] . '%');
            }
            if (isset($filters['alamat']) && $filters['alamat'] != '') {
                $query->where('alamat', 'like', '%' . $filters['alamat'] . '%');
            }

            $data = $query->orderBy('nama_mitra', 'asc')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Data Mitra');

            // Header
            $headers = ['No', 'Kode Mitra', 'Nama Mitra', 'Alamat', 'Tanggal Dibuat'];
            $headerRange = 'A1:E1';

            foreach ($headers as $key => $header) {
                $column = chr(65 + $key); // A, B, C, etc.
                $sheet->setCellValue($column . '1', $header);
            }

            // Style header
            $sheet->getStyle($headerRange)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            // Data
            $row = 2;
            foreach ($data as $index => $item) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $item->kode_mitra);
                $sheet->setCellValue('C' . $row, $item->nama_mitra);
                $sheet->setCellValue('D' . $row, $item->alamat);
                $sheet->setCellValue('E' . $row, $item->created_at ? $item->created_at->format('d/m/Y H:i') : '');
                $row++;
            }

            // Style data
            if ($row > 2) {
                $dataRange = 'A2:E' . ($row - 1);
                $sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);
            }

            // Auto width
            foreach (range('A', 'E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'data_mitra_' . date('Y-m-d_H-i-s') . '.xlsx';

            // Create temp directory if not exists
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $filePath = $tempDir . DIRECTORY_SEPARATOR . $filename;
            $writer->save($filePath);

            return response()->download($filePath)->deleteFileAfterSend();

        } catch (\Exception $e) {
            throw new \Exception('Gagal export Excel: ' . $e->getMessage());
        }
    }

    public function exportWord(array $filters = [])
    {
        try {
            $query = Datamitra::query();

            // Apply filters
            if (isset($filters['kode_mitra']) && $filters['kode_mitra'] != '') {
                $query->where('kode_mitra', 'like', '%' . $filters['kode_mitra'] . '%');
            }
            if (isset($filters['nama_mitra']) && $filters['nama_mitra'] != '') {
                $query->where('nama_mitra', 'like', '%' . $filters['nama_mitra'] . '%');
            }
            if (isset($filters['alamat']) && $filters['alamat'] != '') {
                $query->where('alamat', 'like', '%' . $filters['alamat'] . '%');
            }

            $data = $query->orderBy('nama_mitra', 'asc')->get();

            $phpWord = new PhpWord();

            // Set document properties
            $properties = $phpWord->getDocInfo();
            $properties->setCreator('Training App Bartech');
            $properties->setCompany('Bartech');
            $properties->setTitle('Laporan Data Mitra');
            $properties->setDescription('Laporan Data Mitra yang digenerate otomatis');
            $properties->setSubject('Data Mitra');

            $section = $phpWord->addSection([
                'marginTop' => 1000,
                'marginBottom' => 1000,
                'marginLeft' => 1000,
                'marginRight' => 1000,
            ]);

            // Title
            $section->addText(
                'LAPORAN DATA MITRA',
                ['bold' => true, 'size' => 16, 'name' => 'Arial'],
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
            );
            $section->addTextBreak(1);

            // Date
            $section->addText(
                'Tanggal Cetak: ' . date('d/m/Y H:i:s'),
                ['size' => 10, 'name' => 'Arial'],
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]
            );
            $section->addTextBreak(1);

            // Table
            $table = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 80,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
                'cellSpacing' => 0
            ]);

            // Table header
            $table->addRow(null, ['tblHeader' => true]);
            $table->addCell(800)->addText('No', ['bold' => true, 'size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(2000)->addText('Kode Mitra', ['bold' => true, 'size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(3000)->addText('Nama Mitra', ['bold' => true, 'size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(4000)->addText('Alamat', ['bold' => true, 'size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(2000)->addText('Tanggal Dibuat', ['bold' => true, 'size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

            // Table data
            foreach ($data as $index => $item) {
                $table->addRow();
                $table->addCell(800)->addText($index + 1, ['size' => 9], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $table->addCell(2000)->addText($item->kode_mitra ?? '', ['size' => 9]);
                $table->addCell(3000)->addText($item->nama_mitra ?? '', ['size' => 9]);
                $table->addCell(4000)->addText($item->alamat ?? '', ['size' => 9]);
                $table->addCell(2000)->addText($item->created_at ? $item->created_at->format('d/m/Y H:i') : '', ['size' => 9], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            }

            // Footer
            $section->addTextBreak(1);
            $section->addText(
                'Total Data: ' . count($data) . ' record(s)',
                ['bold' => true, 'size' => 10, 'name' => 'Arial']
            );

            $writer = WordIOFactory::createWriter($phpWord, 'Word2007');
            $filename = 'data_mitra_' . date('Y-m-d_H-i-s') . '.docx';

            // Create temp directory if not exists
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $filePath = $tempDir . DIRECTORY_SEPARATOR . $filename;
            $writer->save($filePath);

            return response()->download($filePath)->deleteFileAfterSend();

        } catch (\Exception $e) {
            throw new \Exception('Gagal export Word: ' . $e->getMessage());
        }
    }

    private function generatePdfHtml($data)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title>Laporan Data Mitra</title>
            <style>
                @page {
                    margin: 15mm;
                    size: A4 portrait;
                }

                * {
                    box-sizing: border-box;
                }

                body {
                    font-family: "DejaVu Sans", Arial, sans-serif;
                    font-size: 11px;
                    line-height: 1.4;
                    margin: 0;
                    padding: 0;
                    color: #333;
                }

                .header {
                    text-align: center;
                    margin-bottom: 25px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 15px;
                }

                .title {
                    font-size: 18px;
                    font-weight: bold;
                    margin-bottom: 8px;
                    color: #000;
                }

                .subtitle {
                    font-size: 12px;
                    color: #666;
                }

                .date {
                    font-size: 10px;
                    text-align: right;
                    margin-bottom: 20px;
                    color: #666;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                    font-size: 10px;
                }

                th, td {
                    border: 1px solid #333;
                    padding: 6px 4px;
                    text-align: left;
                    vertical-align: top;
                    word-wrap: break-word;
                }

                th {
                    background-color: #e8e8e8;
                    font-weight: bold;
                    text-align: center;
                    font-size: 10px;
                }

                .center {
                    text-align: center;
                }

                .no-col {
                    width: 8%;
                    text-align: center;
                }

                .kode-col {
                    width: 18%;
                }

                .nama-col {
                    width: 28%;
                }

                .alamat-col {
                    width: 28%;
                }

                .date-col {
                    width: 18%;
                    text-align: center;
                }

                .footer {
                    margin-top: 20px;
                    font-size: 9px;
                    text-align: left;
                    border-top: 1px solid #ccc;
                    padding-top: 10px;
                    color: #666;
                }

                .page-break {
                    page-break-before: always;
                }

                /* Responsive adjustments */
                @media print {
                    body {
                        font-size: 10px;
                    }
                    .header {
                        margin-bottom: 20px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="title">LAPORAN DATA MITRA</div>
                <div class="subtitle">Sistem Informasi Manajemen Data</div>
            </div>

            <div class="date">Tanggal Cetak: ' . date('d/m/Y H:i:s') . '</div>

            <table>
                <thead>
                    <tr>
                        <th class="no-col">No</th>
                        <th class="kode-col">Kode Mitra</th>
                        <th class="nama-col">Nama Mitra</th>
                        <th class="alamat-col">Alamat</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($data as $index => $item) {
            $html .= '
                    <tr>
                        <td class="center">' . ($index + 1) . '</td>
                        <td>' . htmlspecialchars($item->kode_mitra ?? '') . '</td>
                        <td>' . htmlspecialchars($item->nama_mitra ?? '') . '</td>
                        <td>' . htmlspecialchars($item->alamat ?? '') . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        return $html;
    }
}
