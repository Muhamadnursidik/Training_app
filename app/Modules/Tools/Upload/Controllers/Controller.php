<?php
namespace App\Modules\Tools\Upload;

use App\Bases\BaseModule;
use Illuminate\Http\Request;
use App\Modules\Tools\Upload\Repository;
use App\Modules\Tools\Upload\Service;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo         = $repo;
        $this->module       = 'tools.Upload';
        $this->pageTitle    = __('Tools');
        $this->pageSubTitle = __('Upload Files');
    }

    public function index()
    {
        activity('Akses menu')->log('Akses menu ' . $this->pageSubTitle);

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

    public function download(Request $request, $id){
        $request->merge(['_id' => decrypt($id)]);
        return $this->repo->startProcess('download', $request);
    }

    public function destroy(Request $request, $id)
    {
        $request->merge(['_id' => decrypt($id)]);
        $result = $this->repo->startProcess('destroy', $request);
        return $this->serveJSON($result);
    }
}
