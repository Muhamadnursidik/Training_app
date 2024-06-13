<?php
namespace App\Modules\Admin\Role;

use App\Bases\BaseModule;
use App\Libraries\AuthAPI;
use Illuminate\Http\Request;
use App\Modules\Admin\Role\Repository;
use App\Modules\Admin\Role\Service;
use App\Modules\Master\Unitkerja\Service as UnitkerjaService;
use App\Modules\Master\Jabatan\Service as JabatanService;
use App\Modules\Master\Bagian\Service as BagianService;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo         = $repo;
        $this->module       = 'admin.role';
        parent::__construct();
    }

    public function index()
    {
        activity('Akses menu')->log('Akses menu ' . $this->pageTitle);

        \MenuHelper::generate('admin');
        return $this->serveView();
    }

    public function data(Request $request)
    {
        $result = $this->repo->startProcess('data', $request);
        return $this->serveJSON($result);
    }

    public function getMenus(Request $request)
    {
        $result = $this->repo->startProcess('get-menus', $request);
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
            'data'         => $data,
            'permissions'  => Service::hasPermissions(decrypt($id)),
            'visibilities' => Service::hasVisibilities(decrypt($id)),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->merge(['_id' => decrypt($id)]);
        $result = $this->repo->startProcess('update', $request);
        return $this->serveJSON($result);
    }

    public function destroy(Request $request, $id)
    {
        $request->merge(['_id' => decrypt($id)]);
        $result = $this->repo->startProcess('destroy', $request);
        return $this->serveJSON($result);
    }

    public function destroys(Request $request)
    {
        $result = $this->repo->startProcess('destroys', $request);
        return $this->serveJSON($result);
    }

    public function mapping($id){
        $data = Service::get(decrypt($id));
        $mapping =  Service::mappingData(decrypt($id));

        return $this->serveView([
            'data'              => $data,
            'mapping'           => $mapping,
            'options_jabatan'   => JabatanService::dropdown(),
            'options_bagian'    => BagianService::dropdown('N'),
            'options_unit_kerja'=> UnitkerjaService::dropdown()
        ]);
    }

    public function mappingstore(Request $request, $id){
        $request->merge(['_id' => decrypt($id)]);
        $result = $this->repo->startProcess('mappingstore', $request);
        return $this->serveJSON($result);
    }

    public function report(Request $request, $id)
    {
        $data = Service::mappingReport(decrypt($id));
        $role = Service::get(decrypt($id));
        $type           = $request->get('type');
        $path = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'role');
        
        if (!Storage::exists('role')) {
            Storage::makeDirectory('role');
        }
        if ($type == 'export-excel') {
            libxml_use_internal_errors(true);

            $reader = new Html();
            $spreadsheet = $reader->loadFromString($this->serveView(compact('data', 'type', 'role')));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN
                    )
                )
            );

            $spreadsheet->getActiveSheet()->getStyle(
                'A4:' . $spreadsheet->getActiveSheet()->getHighestColumn() . $spreadsheet->getActiveSheet()->getHighestRow()
            )->applyFromArray($styleArray);

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = DIRECTORY_SEPARATOR . str_replace(' ', '_', $this->pageTitle) . '.xlsx';

            $writer->save($path.$filename);
            
            return Storage::download('public'.DIRECTORY_SEPARATOR.'role'.$filename);
        }
    }
    
    public function duplicate(Request $request, $id)
    {
        $request->merge(['_id' => decrypt($id)]);
        $result = $this->repo->startProcess('duplicate', $request);
        return $this->serveJSON($result);
    }
}
