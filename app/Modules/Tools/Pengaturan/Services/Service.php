<?php
namespace App\Modules\Tools\Pengaturan;

use App\Bases\BaseService;
use DataTables;
use App\Modules\Tools\Pengaturan\Model;
use Illuminate\Support\Facades\Config as Config;
use Intervention\Image\Facades\Image;

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

    public static function getPengaturan()
    {
        $query = Model::isActive()->orderBy('id','asc')->get();
        if ($query) {
            return $query;
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
            ], function($query, $event, $cursor) use ($data){
                
                
            });
        });
    }

    public function setConfig($config, $return = 'encode'){
        $config_data = [];
        if(sizeof($config) > 0){
            foreach($config as $value){
                if ($value['tipe'] == 'upload') {
                    $file = $value['value'];
                    if ($file != '') {
                        if ($value['key'] == 'icon_file') {
                            $path = public_path();
                            $filename = 'favicon.ico'; // Adjust the filename as needed
                        } else {
                            $path = public_path('img');
                            $filename = 'logo.png'; // Adjust the filename as needed
                        }
    
                        $fullPath = $path . '/' . $filename;
                        // Check if the file already exists
                        if (file_exists($fullPath)) {
                            // Delete the existing file
                            unlink($fullPath);
                        }

                        $filePath = $file->move($path, $filename);

                        if ($value['key'] != 'icon_file') {
                            // Resize the image to 160x60
                            // $resizedImagePath = public_path('img/' . $filename);
                            // Image::make($filePath)->resize(160, 60)->save($resizedImagePath);
                        }
                        
                        $value['value'] = $filename;
                    }
                    $config_data[] = $value;
                } else {
                    $config_data[] = $value;
                }
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
}
