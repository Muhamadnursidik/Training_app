<?php
namespace App\Modules\Tools\Appconfig;

use App\Bases\BaseService;
use DataTables;
use App\Modules\Tools\Appconfig\Model;
use Illuminate\Support\Facades\Config as Config;

class Service extends BaseService
{

    public function __construct()
    {
    }

    public function data(array $data)
    {   
        $query = Model::data();
    
        return DataTables::of($query)
            ->filter(function($query) use ($data) {

                if ($data['code'] != ''){
                    $query->where('code','ILIKE','%'.$data['code'].'%');
                }

                if ($data['status'] != ''){
                    $query->where('status', $data['status']);
                }

            })
            ->addColumn('id', function($query) {
                return encrypt($query->id);
            })
            ->make(true)
            ->getData(true);
    }

    public function store(array $data)
    {
        return Model::transaction(function() use ($data) {
            return Model::createOne([
                'config'            => $this->setConfig($data['config']),
                'code'              => $data['code'],
                'status'            => $data['status'] ? 1 : 0,
            ], function($query, $event) use ($data){
            });
        });
    }

    public static function get($id)
    {
        if($id){
            $query = Model::find($id);
            if ($query) {
                return $query;
            }
        }

        return false;
    }

    public static function getbycode($code)
    {
        if($code){
            $query = Model::where('code',$code)->first();
            if ($query) {
                return $query;
            }
        }

        return false;
    }

    public function update(array $data) {
        return Model::transaction(function() use ($data) {
            return Model::updateOne($data['id'], [
                'config'   => $this->setConfig($data['config']),
                'code'   => $data['code'],
                'status' => $data['status'] ? 1 : 0
            ], function($query, $event, $cursor) use ($data){
                
                
            });
        });
    }

    public function destroy(array $data) {
        return Model::deleteOne($data['id']);
    }

    public function destroys(array $data) {
        $id = [];
        foreach ($data['id'] as $value) {
            $id[] = decrypt($value);
        }

        return Model::transaction(function() use ($id) {
            return Model::deleteBatch($id);
        });
    }

    public function setConfig($config, $return = 'encode'){
        $config_data = [];
        if(sizeof($config) > 0){
            foreach($config as $value){
                $config_data[] = $value;
            }
        }

        return ($return == 'decode') ? json_decode($config_data) : json_encode($config_data);
    }

    public static function count(){
        return Model::count();
    }
    
    public static function getAll()
    {
        return Model::get();
    }

    public static function initConfig($code){
        $config_array = self::getbycode($code);

        $config = [];
        foreach (json_decode($config_array->config,true) as $value) {
            
            if(strpos($value['key'], '_')){
                $exp = explode('_',$value['key']);
                $config[$exp[0]][$exp[1]] = $value['value'];
            }else{
                $config[$value['key']] = $value['value'];
            }
        }
        
        Config::set($code, $config);
    }

    public static function getConfig($code){
        $config_array = self::getbycode($code);
        $config = [];
        foreach (json_decode($config_array->config,true) as $value) {
            $config[$value['key']] = $value['value'];
        }

        return $config;
    }

    public function unlockconfig(array $data)
    {
        session(['unlocked_appconfig' => true]);
        return redirect()->route('tools.appconfig.index');
    }
}
