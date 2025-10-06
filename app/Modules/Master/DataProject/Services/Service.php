<?php

namespace App\Modules\Master\DataProject\Services;

use App\Bases\BaseService;
use App\Modules\Master\DataProject\Models\DataProject;
use App\Modules\Master\DataMitra\Models\Datamitra;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use Dompdf\Dompdf;
use Dompdf\Options;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class Service extends BaseService
{
    public function __construct() {}

    public function data(array $data)
    {
        $query = DataProject::with('mitra');

        return FacadesDataTables::of($query)
            ->filter(function ($query) use ($data) {
                if (!empty($data['kode_project'])) {
                    $query->where('kode_project', 'ILIKE', '%' . $data['kode_project'] . '%');
                }

                if (!empty($data['nama_project'])) {
                    $query->where('nama_project', 'ILIKE', '%' . $data['nama_project'] . '%');
                }

                if (!empty($data['mitra_id'])) {
                    $query->where('mitra_id', $data['mitra_id']);
                }
            })

            ->addColumn('id', fn($query) => encrypt($query->id))
            ->addColumn('mitra_kode', fn($query) => $query->mitra?->kode_mitra)
            ->make(true);

    }

    public function store(array $data)
    {
        if (empty($data['tanggal_mulai']) || empty($data['tanggal_akhir'])) {
            throw new \Exception('Tanggal mulai dan tanggal akhir wajib diisi');
        }

        if ($data['tanggal_mulai'] > $data['tanggal_akhir']) {
            throw new \Exception('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        }

        return DataProject::create([
            'kode_project'   => $data['kode_project'],
            'mitra_id'       => $data['mitra_id'],
            'nama_project'   => $data['nama_project'],
            'tanggal_mulai'  => $data['tanggal_mulai'],
            'tanggal_akhir'  => $data['tanggal_akhir'],
        ]);
    }

    public static function get($id)
    {
        $query = DataProject::with('mitra')->find($id);
        if ($query) {
            return $query;
        }
        throw new \Exception('Data Project dengan ID ' . $id . ' tidak ditemukan');
    }

    public function update(array $data)
    {
        $model = DataProject::find($data['id']);
        if (!$model) {
            throw new \Exception('Data Project tidak ditemukan');
        }

        if (
            !empty($data['tanggal_mulai']) && !empty($data['tanggal_akhir']) &&
            $data['tanggal_mulai'] > $data['tanggal_akhir']
        ) {
            throw new \Exception('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        }

        return $model->update([
            'kode_project'   => $data['kode_project'],
            'mitra_id'       => $data['mitra_id'],
            'nama_project'   => $data['nama_project'],
            'tanggal_mulai'  => $data['tanggal_mulai'] ?? null,
            'tanggal_akhir'  => $data['tanggal_akhir'] ?? null,
        ]);
    }

    public function destroy(array $data)
    {
        $model = DataProject::find($data['id']);
        if (!$model) {
            throw new \Exception('Data Project tidak ditemukan');
        }

        // Rule: project tidak boleh dihapus jika masih terkait dengan mitra
        if ($model->mitra) {
            throw new \Exception('Project tidak bisa dihapus karena masih terkait dengan Mitra');
        }

        return $model->forceDelete();
    }

    public function destroys(array $data)
    { 
        $ids = array_map(fn($id) => decrypt($id), $data['id'] ?? []);

        $projects = DataProject::whereIn('id', $ids)->get();

        foreach ($projects as $project) {
            if ($project->mitra) {
                throw new \Exception("Project {$project->nama_project} tidak bisa dihapus karena masih terkait dengan Mitra");
            } 
            $project->forceDelete();
        }

        return true;
    }

    public static function count()
    {
        return DataProject::count();
    }

    public static function dropdown($default = '')
    {
        $results = [];

        if ($default) {
            $results[''] = __('Pilih');
        }

        $cursors = Datamitra::orderBy('kode_mitra', 'asc')->get();

        foreach ($cursors as $cursor) {
            $results[$cursor->id] = $cursor->kode_mitra;
        }

        return $results;
    }

    public function exportPdf(array $filters = [])
    {
        try {
            $query = DataProject::with('mitra');

            if (!empty($filters['kode_project'])) {
                $query->where('kode_project', 'like', '%' . $filters['kode_project'] . '%');
            }
            if (!empty($filters['nama_project'])) {
                $query->where('nama_project', 'like', '%' . $filters['nama_project'] . '%');
            }
            if (!empty($filters['mitra_id'])) {
                $query->where('mitra_id', $filters['mitra_id']);
            }

            $data = $query->orderBy('nama_project', 'asc')->get();
            $html = $this->generatePdfHtml($data);

            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'data_project_' . date('Y-m-d_H-i-s') . '.pdf';

            return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            throw new \Exception('Gagal export PDF: ' . $e->getMessage());
        }
    }

    public function exportExcel(array $filters = [])
    {
        try {
            $query = DataProject::with('mitra');

            if (!empty($filters['kode_project'])) {
                $query->where('kode_project', 'like', '%' . $filters['kode_project'] . '%');
            }
            if (!empty($filters['nama_project'])) {
                $query->where('nama_project', 'like', '%' . $filters['nama_project'] . '%');
            }
            if (!empty($filters['mitra_id'])) {
                $query->where('mitra_id', $filters['mitra_id']);
            }

            $data = $query->orderBy('nama_project', 'asc')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet()->setTitle('Data Project');

            $headers = ['No', 'Kode Project', 'Nama Project', 'Mitra', 'Tanggal Mulai', 'Tanggal Akhir'];
            $sheet->fromArray($headers, null, 'A1');

            $row = 2;
            foreach ($data as $index => $item) {
                $sheet->setCellValue("A$row", $index + 1);
                $sheet->setCellValue("B$row", $item->kode_project);
                $sheet->setCellValue("C$row", $item->nama_project);
                $sheet->setCellValue("D$row", $item->mitra?->kode_mitra);
                $sheet->setCellValue("E$row", $item->tanggal_mulai?->format('Y-m-d'));
                $sheet->setCellValue("F$row", $item->tanggal_akhir?->format('Y-m-d'));
                $row++;
            }

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'data_project_' . date('Y-m-d_H-i-s') . '.xlsx';

            $filePath = storage_path('app/temp/' . $filename);
            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
            }

            $writer->save($filePath);

            return response()->download($filePath)->deleteFileAfterSend();
        } catch (\Exception $e) {
            throw new \Exception('Gagal export Excel: ' . $e->getMessage());
        }
    }

    public function exportWord(array $filters = [])
    {
        try {
            $query = DataProject::with('mitra');

            if (!empty($filters['kode_project'])) {
                $query->where('kode_project', 'like', '%' . $filters['kode_project'] . '%');
            }
            if (!empty($filters['nama_project'])) {
                $query->where('nama_project', 'like', '%' . $filters['nama_project'] . '%');
            }
            if (!empty($filters['mitra_id'])) {
                $query->where('mitra_id', $filters['mitra_id']);
            }

            $data = $query->orderBy('nama_project', 'asc')->get();

            $phpWord = new PhpWord();
            $section = $phpWord->addSection();

            $section->addText('LAPORAN DATA PROJECT', ['bold' => true, 'size' => 16]);
            $section->addText('Tanggal Cetak: ' . date('d/m/Y H:i:s'));

            $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
            $table->addRow();
            foreach (['No', 'Kode Project', 'Nama Project', 'Mitra', 'Tanggal Mulai', 'Tanggal Akhir'] as $header) {
                $table->addCell(2000)->addText($header, ['bold' => true]);
            }

            foreach ($data as $index => $item) {
                $table->addRow();
                $table->addCell(800)->addText($index + 1);
                $table->addCell(2000)->addText($item->kode_project ?? '');
                $table->addCell(3000)->addText($item->nama_project ?? '');
                $table->addCell(3000)->addText($item->mitra?->kode_mitra ?? '');
                $table->addCell(2000)->addText($item->tanggal_mulai?->format('d/m/Y') ?? '');
                $table->addCell(2000)->addText($item->tanggal_akhir?->format('d/m/Y') ?? '');
            }

            $filename = 'data_project_' . date('Y-m-d_H-i-s') . '.docx';
            $filePath = storage_path('app/temp/' . $filename);
            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
           }

           $writer = WordIOFactory::createWriter($phpWord, 'Word2007');
           $writer->save($filePath);

           return response()->download($filePath)->deleteFileAfterSend();
       } catch (\Exception $e) {
            throw new \Exception('Gagal export Word: ' . $e->getMessage());
        }
    }

    private function generatePdfHtml($data)
    {
        $html = '<h2 style="text-align:center;">LAPORAN DATA PROJECT</h2>';
        $html .= '<table width="100%" border="1" cellspacing="0" cellpadding="5">';
        $html .= '<tr>
            <th>No</th>
            <th>Kode Project</th>
            <th>Nama Project</th>
            <th>Mitra</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Akhir</th>
        </tr>';

        foreach ($data as $index => $item) {
            $html .= '<tr>
                <td>' . ($index + 1) . '</td>
                <td>' . htmlspecialchars($item->kode_project ?? '') . '</td>
                <td>' . htmlspecialchars($item->nama_project ?? '') . '</td>
                <td>' . htmlspecialchars($item->mitra?->kode_mitra ?? '') . '</td>
                <td>' . ($item->tanggal_mulai?->format("Y-m-d") ?? '') . '</td>
                <td>' . ($item->tanggal_akhir?->format("Y-m-d") ?? '') . '</td>
            </tr>';
        }

        $html .= '</table>';
        return $html;
    }
}