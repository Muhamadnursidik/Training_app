<?php
namespace App\Modules\Tools\Backup;

use App\Bases\BaseService;
use DataTables;
use App\Modules\Tools\Backup\Model;
use Spatie\DbDumper\Databases\PostgreSql;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon, File;

class Service extends BaseService
{
    protected $storage_via;

    public function __construct()
    {
        $this->storage_via = config('app.storage_via');
    }

    public function data(array $data)
    {   
        $query = Model::data();
    
        return DataTables::of($query)
            ->filter(function($query) use ($data) {

                if ($data['name'] != ''){
                    $query->whereLike('name', $data['name']);
                }
            })
            ->addColumn('id', function($query) {
                return encrypt($query->id);
            })
            ->addColumn('created_at', function($query) {
                return Carbon::parse($query->created_at)->isoFormat('DD MMM YYYY HH:mm:ss');
            })
            ->make(true)
            ->getData(true);
    }

    public function createDirectory($dirname = '')
    {
        $path = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR.$dirname);

        if (!File::isDirectory($path)) {
            File::makeDirectory($path);
        }
    }

    public function backup(array $data)
    {    
        $storage_via = $this->storage_via;
        
        $filename = config('database.connections.pgsql.database').'_'.date('Ymd-his').'.backup';
        $this->createDirectory('backup');
        $storage = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR.'backup'. DIRECTORY_SEPARATOR.$filename);
        
        return Model::transaction(function() use ($filename, $storage, $data) {
            return Model::createOne([
                'name' => $filename
            ], function($query, $event) use ($filename, $storage, $data){

                $backup = PostgreSql::create()
                ->setDbName(config('database.connections.pgsql.database'))
                ->setUserName(config('database.connections.pgsql.username'))
                ->setPassword(config('database.connections.pgsql.password'))
                ->setPort(config('database.connections.pgsql.port'))
                ->setHost(config('database.connections.pgsql.host'))
                ->addExtraOption('--schema=' . config('database.connections.pgsql.schema'))
                ->addExtraOption('--no-owner')
                ->addExtraOption('--format=c')
                ->setDumpBinaryPath(config('database.connections.pgsql.dump.dump_binary_path'))
                ->dumpToFile($storage);
                
                if($this->storage_via == 's3'){
                    $filename_s3 = Storage::disk('s3')->put('backup/'.$filename, file_get_contents($storage));
                }
               

            
            });
        });
        
    }

    public function cleanTransaction($data){
        $transaction_table = [
            't_action_plan',
            't_activity_log',
            't_data_posisi_kuantitatif',
            't_notification',
            't_penetapan_risk_appetite',
            't_penilaian_eksposur_risiko',
            't_realisasi_action_plan',
            't_validation_data_posisi_kuantitatif',
            't_validation_ekposur_risiko',
            't_validation_penetapan_risk_appetite'
        ];

        foreach ($transaction_table as $table) {
            DB::table($table)->truncate();
        }
    }

    public static function get($id)
    {
        $query = Model::find($id);
        if ($query) {
            return $query;
        }

        return false;
    }

    public function destroy(array $data) 
    {
        //hapus file
        $qdata = Model::find($data['id']);
        //end hapus file

        $storage_via = (new self)->storage_via;
  
        return Model::deleteOne($data['id'], function ($query, $event, $cursor) use ($storage_via, $qdata) {
            if ($storage_via == 'local') {
                unlink(storage_path('app/public/backup/'.$qdata->name));
            } elseif ($storage_via == 's3') {
                Storage::disk('s3')->delete('backup/'.$qdata->name);
            }
        });
    }
}
