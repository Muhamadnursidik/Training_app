<?php

namespace App\Modules\Tools\Activitylog;

use App\Bases\BaseModule;
use Illuminate\Http\Request;
use App\Modules\Tools\Activitylog\Repository;
use App\Modules\Tools\Activitylog\Service;
use Illuminate\Support\Facades\Storage;
use App\Modules\Admin\User\Service as UserService;
use App\Libraries\ExportToWord;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PDF;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo         = $repo;
        $this->module       = 'tools.activitylog';
        parent::__construct();
    }

    public function index()
    {
        activity('Akses menu')->log('Akses menu ' . $this->pageTitle);
        return $this->serveView([
            'options_user' => UserService::dropdown_username_nama('Semua'),
        ]);
    }

    public function data(Request $request)
    {
        $result = $this->repo->startProcess('data', $request);
        return $this->serveJSON($result);
    }

    public function detail(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');

        if (base64_decode($id, true) !== false) {
            $id = decrypt($id);
        }

        $data = Service::get($id);
        $tabelTerkait = '';
        if ($data->subject_type != null) {
            $tabelTerkait = app($data->subject_type)->getTable();
        }
        $config = [
            'format' => 'A4',
            'temp_dir' => storage_path('app/public/DetailLogactivity')
        ];

        if ($type == 'export-pdf') {
            $pdf = PDF::loadview($this->getViewPath(), compact(
                'data',
                'tabelTerkait',
                'type'
            ), [], $config);
            return $pdf->stream($this->pageTitle . '-' . date('Y-m-d H:i:s', strtotime($data->created_at)) . '.pdf');
        } else {
            return $this->serveView([
                'data' => $data,
                'tabelTerkait' => $tabelTerkait,
                'type' => $type
            ]);
        }
    }

    public function export(Request $request)
    {
        $type         = $request->get('type');
        $filter         = $request->all();
        $data         = $this->repo->startProcess('data', $request);
        
        $path = storage_path('app/public/logactivity');
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }
        
        if ($request->get('type') == 'export-word') {
            $html = $this->serveView(compact(
                'data',
                'type',
                'filter'
            ));

            ExportToWord::htmlToDoc($html, '', storage_path('app/public/logactivity/' .  $this->pageSubTitle . '-Log-Activity' . '.doc'));
            return response()->download(storage_path('app/public/logactivity/' .  $this->pageSubTitle . ' -Log-Activity' . '.doc'))->deleteFileAfterSend(true);
        } elseif ($request->get('type') == 'export-pdf') {
            $config = [
                'format' => 'A4-L',
                'temp_dir' => storage_path('app/public/logactivity/')
            ];
            $pdf = PDF::loadview($this->getViewPath(), compact(
                'data',
                'type',
                'filter'
            ), [], $config);
            return $pdf->stream($this->pageSubTitle . '-Log-Activity' . '.pdf');
        } elseif ($type == 'export-excel') {
            libxml_use_internal_errors(true);

            $reader = new Html();
            $spreadsheet = $reader->loadFromString($this->serveView(compact('data', 'type', 'filter'), '', true));
            $generalStyle = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN
                    )
                )
            );

            $spreadsheet->getActiveSheet()->getStyle(
                'A7:' . $spreadsheet->getActiveSheet()->getHighestColumn() . $spreadsheet->getActiveSheet()->getHighestRow()
            )->applyFromArray($generalStyle);

            $lastRowOfJ = $spreadsheet->getActiveSheet()->getHighestDataRow('J');
            $noBorderStyle = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_NONE
                    )
                )
            );

            $columnJRange = 'J1:J' . $lastRowOfJ;

            $spreadsheet->getActiveSheet()->getStyle($columnJRange)->applyFromArray($noBorderStyle);

            $lastRowAllCol = $spreadsheet->getActiveSheet()->getHighestDataRow();
            foreach ($spreadsheet->getActiveSheet()->getColumnIterator() as $column) {
                $columnLetter = $column->getColumnIndex();
                $lastrowRange = $columnLetter . $lastRowAllCol;
                $spreadsheet->getActiveSheet()->getStyle($lastrowRange)->applyFromArray($noBorderStyle);
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'public/logactivity/' . str_replace(' ', '_', $this->pageTitle) . '.xlsx';

            $writer->save(storage_path('app/') . $filename);

            return Storage::download($filename);
        }
    }
}
