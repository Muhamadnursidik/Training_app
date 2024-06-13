<?php
namespace App\Modules\Mapping\Notifications;

use App\Bases\BaseModule;
use App\Mail\LedReviewMail;
use Illuminate\Http\Request;
use App\Modules\Mapping\Notifications\Repository;
use App\Modules\Mapping\Notifications\Service;
use App\Modules\Mapping\Notifications\Model;
use App\Modules\Master\Unitkerja\Service as UnitKerjaService;
use Illuminate\Support\Facades\Mail;
use App\Modules\Admin\User\Model as UserModel;
use Exception;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo         = $repo;
        $this->module       = 'mapping.notifications';
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

    public function list()
    {
        return $this->serveView();
    }

    public function listData(Request $request)
    {
        $result = $this->repo->startProcess('listData', $request);
        return $this->serveJSON($result);
    }

    public function count(Request $request)
    {
        $result = $this->repo->startProcess('count', $request);
        return $this->serveJSON($result);
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

    public function read($id)
    {
        $data = Service::get(decrypt($id));
        $data->HasRead(decrypt($id));

        $parameter = [
                        'inputkejadian'   => encrypt($data->source_id), 
                        'tindaklanjut'    => encrypt($data->source_id),
                        'penetapan'       => encrypt($data->source_id),
                        'pengajuankredit' => encrypt($data->source_id),
                        'id'              => encrypt($data->source_id),
                        'nid'             => $data->id,
                        'unit_kerja_code' => $data->unit_kerja_code,
                    ];

        return redirect()->route($data->url,$parameter);
    }

    public function sendmail(){
        try{

            $data = ['msg' => 'Terdapat input kejadian yang harus anda review/setujui','url' => env('APP_URL')];
            Mail::to('koala.jengke@gmail.com')->send(new LedReviewMail($data));
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
