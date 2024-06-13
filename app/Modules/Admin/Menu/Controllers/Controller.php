<?php
namespace App\Modules\Admin\Menu;

use App\Bases\BaseModule;
use Illuminate\Http\Request;
use App\Modules\Admin\Menu\Repository;
use App\Modules\Admin\Menu\Service;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo         = $repo;
        $this->module       = 'admin.menu';
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

    public function create(Request $request)
    {
        return $this->serveView([
            'parent_id'   => !empty($request->parent_id) ? $request->parent_id : '',
            'routes'      => Service::getRoutesAdmin(__('Pilih')),
            'permissions' => Service::getPermission(__('Pilih')),
            'options_actions'     => config('actions')
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->repo->startProcess('store', $request);
        return $this->serveJSON($result);
    }

    public function edit(Request $request, $id)
    {
        $data = Service::get(decrypt($id));
        return $this->serveView([
            'data'        => $data,
            'features'    => Service::hasPermissions(decrypt($id)),
            'routes'      => Service::getRoutesAdmin(__('Pilih')),
            'permissions' => Service::getPermission(__('Pilih')),
            'options_actions'     => config('actions')
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

    public function saveOrder(Request $request)
    {
        $result = $this->repo->startProcess('save-order', $request);
        return $this->serveJSON($result);
    }
}
