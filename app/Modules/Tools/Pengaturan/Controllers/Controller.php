<?php
namespace App\Modules\Tools\Pengaturan;

use App\Bases\BaseModule;
use Illuminate\Http\Request;
use App\Modules\Tools\Pengaturan\Repository;
use App\Modules\Tools\Pengaturan\Service;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo         = $repo;
        $this->module       = 'tools.pengaturan';
        parent::__construct();
    }

    public function index()
    {
        activity('Akses menu')->log('Akses menu ' . $this->pageTitle);
        $pengaturan = Service::getPengaturan();
        $count = Service::count();
        return $this->serveView(['count' => $count, 'pengaturan' => $pengaturan]);
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
        return $this->serveView(['data' => $data]);
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

    public function page(Request $request)
    {
        return $this->serveView([
            'tab'  => $request->get('tab'),
            'data' => Service::getbycode($request->get('tab')),
        ]);
    }
}
