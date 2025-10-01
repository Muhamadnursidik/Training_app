<?php
namespace App\Modules\Master\Penyesuaianrencanaproject\Controllers;

use App\Bases\BaseModule;
use App\Modules\Master\Penyesuaianrencanaproject\Models\Model;
use App\Modules\Master\Penyesuaianrencanaproject\Repositories\Repository;
use App\Modules\Master\Penyesuaianrencanaproject\Services\Service;
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
        $this->module  = 'master.penyesuaianrencanaproject'; 
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
        $parents = Model::select('id', 'aktivitas', 'level')
            ->orderBy('level')->orderBy('aktivitas')
            ->get()
            ->map(function ($item) {
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
        $result = $this->repo->startProcess('store', $request);
        return $this->serveJSON($result);
    }

    public function edit($id)
    {
        $data = $this->service->get(decrypt($id));
        return $this->serveView(['data' => $data]);
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

}
