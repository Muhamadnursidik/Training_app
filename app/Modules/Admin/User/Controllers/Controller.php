<?php
namespace App\Modules\Admin\User;

use App\Bases\BaseModule;
use Illuminate\Http\Request;
use App\Modules\Admin\User\Repository;
use App\Modules\Admin\User\Service;
use App\Modules\Admin\Role\Service as RoleService;
use App\Modules\Master\Unitkerja\Service as UnitkerjaService;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo         = $repo;
        $this->module       = 'admin.user';
        parent::__construct();
    }

    public function index()
    {
        activity('Akses menu')->log('Akses menu ' . $this->pageTitle);

        return $this->serveView([ 
            'role_options' => RoleService::dropdown(),
            'options_unit_kerja' => UnitkerjaService::dropdown(true, null, false),
            'username'  => config('data.login_auth_with') ? config('data.login_auth_with') : 'username'
            ]);
    }

    public function data(Request $request)
    {
        $result = $this->repo->startProcess('data', $request);
        return $this->serveJSON($result);
    }

    public function create()
    {
        return $this->serveView([
            'role_options' => Service::roleOptions(), 
            'options_unit_kerja' => UnitkerjaService::dropdown()
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
        return $this->serveView([
            'data' => $data,
            'roles' => Service::getRoles(decrypt($id)),
            'role_options' => Service::roleOptions(),
            'options_unit_kerja' => UnitkerjaService::dropdown()
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

    public function formchangepassword(){
        return $this->serveView([]);
    }

    public function changePassword(Request $request){
        $result = $this->repo->startProcess('change-password', $request);
        return $this->serveJSON($result);
    }

    public function resetPassword(Request $request){
        $result = $this->repo->startProcess('reset-password', $request);
        return $this->serveJSON($result);
    }

    public function release(Request $request){
        $result = $this->repo->startProcess('release', $request);
        return $this->serveJSON($result);
    }

    public function import(){
        return $this->serveView([]);
    }

    public function importuser(Request $request){
        $result = $this->repo->startProcess('import-user', $request);
        return $this->serveJSON($result);
    }

}
