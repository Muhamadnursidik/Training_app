<?php
namespace App\Modules\Tools\Backup;

use Exception;
use App\Bases\BaseModule;
use Illuminate\Http\Request;
use App\Modules\Tools\Backup\Repository;
use App\Modules\Tools\Backup\Service;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseModule
{
    private $repo, $storage_via;

    public function __construct(Repository $repo)
    {
        $this->repo         = $repo;
        $this->module       = 'tools.backup';
        parent::__construct();
        $this->storage_via  = config('app.storage_via');
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
        return $this->serveView();
    }

    public function run(Request $request)
    {
        $result = $this->repo->startProcess('backup', $request);
        return $this->serveJSON($result);
    }

    public function destroy(Request $request, $id)
    {
        $request->merge(['_id' => decrypt($id)]);
        $result = $this->repo->startProcess('destroy', $request);
        return $this->serveJSON($result);
    }

    public function backupclean(Request $request)
    {
        $result = $this->repo->startProcess('backupclean', $request);
        return $this->serveJSON($result);
    }

    public function download(Request $request){
        try{
            $filename = $request->get('file');
            if ($this->storage_via == 'local') {
                return Storage::disk('public')->download('backup/' . DIRECTORY_SEPARATOR . $filename);
            } elseif ($this->storage_via == 's3') {
                return Storage::disk('s3')->download('backup/'.$filename,$filename);
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
        // return Storage::download($request->get('file'));
    }
}
