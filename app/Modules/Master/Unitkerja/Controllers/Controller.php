<?php
namespace App\Modules\Master\Unitkerja;

use App\Bases\BaseModule;
use Illuminate\Http\Request;
use App\Modules\Master\Unitkerja\Repository;
use App\Modules\Master\Unitkerja\Service;
use App\Modules\Master\Tipeunitkerja\Service as TipeunitkerjaService;
use App\Modules\Master\Unitkerja\Service as UnitkerjaService;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo         = $repo;
        $this->module       = 'master.unitkerja';
        parent::__construct();
    }

    public function index()
    {
        activity('Akses menu')->log('Akses menu ' . $this->pageTitle);
        return $this->serveView([
            'options_tipe_unit_kerja' => TipeunitkerjaService::dropdown()
        ]);
    }

    public function data(Request $request)
    {
        $result = $this->repo->startProcess('data', $request);
        return $this->serveJSON($result);
    }

    public function create()
    {
        $unitkerja = UnitkerjaService::dropdown();
        unset($unitkerja['']);

        return $this->serveView([
            'options_tipe_unit_kerja' => TipeunitkerjaService::dropdown(),
            'options_unit_kerja' => $unitkerja,
            'options_tipe_unit_kerja_attr' => TipeunitkerjaService::dropdownAttributes()
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->repo->startProcess('store', $request);
        return $this->serveJSON($result);
    }

    public function edit($id)
    {
        $data = Service::get(decrypt($id));
        $unitkerja = UnitkerjaService::dropdown();
        unset($unitkerja['']);
        
        return $this->serveView([
            'data' => $data,
            'options_tipe_unit_kerja' => TipeunitkerjaService::dropdown(),
            'options_unit_kerja' => $unitkerja,
            'options_tipe_unit_kerja_attr' => TipeunitkerjaService::dropdownAttributes()
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

    public function restore(Request $request, $id)
    {
        $request->merge(['_id' => decrypt($id)]);
        $result = $this->repo->startProcess('restore', $request);
        return $this->serveJSON($result);
    }
    
    public function download(Request $request)
    {
        $result = $this->repo->startProcess('download', $request);
        return $result;
    }

    public function import()
    {
        return $this->serveView([]);
    }

    public function importPost(Request $request)
    {
        $result = $this->repo->startProcess('import', $request);
        return $this->serveJSON($result);
    }

    public function generatedropdownbytipe(Request $request)
    {
        $tipe_code = $request->get('tipe_unit_kerja_code');
        if (isset($tipe_code)) {
            $result = Service::dropdown(false, $tipe_code, false);
        } else {
            $result = [];
        }
        return $result;
    }

    public function generatedropdownbagian(Request $request)
    {
        $result = Service::getBagian($request->get('unit_kerja_code'));
        return $this->serveJSON(['data' => $result]);
    }
}
