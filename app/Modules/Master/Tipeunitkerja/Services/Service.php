<?php
namespace App\Modules\Master\Tipeunitkerja;

use App\Bases\BaseService;
use DataTables;
use App\Modules\Master\Tipeunitkerja\Model;

class Service extends BaseService
{

    public function __construct()
    {
    }

    public function data(array $data)
    {   
        $query = Model::withTrashed()->data();
    
        return DataTables::of($query)
            ->filter(function($query) use ($data) {

                if ($data['name'] != ''){
                    $query->whereLike('name', $data['name']);
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
                'name'              => $data['name'],
                'code'             => $data['code'],
                'status'            => $data['status'] ? 1 : 0,
            ], function($query, $event) use ($data){
            });
        });
    }

    public static function get($id)
    {
        $query = Model::find($id);
        if ($query) {
            return $query;
        }

        return false;
    
    }
    public static function getByCode($code)
    {
        $query = Model::where('code',$code)->get();
        if ($query) {
            return $query;
        }

        return false;
    }

    public function update(array $data) {
        return Model::transaction(function() use ($data) {
            return Model::updateOne($data['id'], [
                'name'   => $data['name'],
                'code'   => $data['code'],
                'status' => $data['status'] ? 1 : 0
            ], function($query, $event, $cursor) use ($data){
            });
        });
    }

    public function destroy(array $data) {
        return Model::deleteOne($data['id'], 
            function($query, $event, $cursor) {
                $cursor->update(['status' => false]);
        });
    }

    public function destroys(array $data) {
        $id = [];
        foreach ($data['id'] as $value) {
            $id[] = decrypt($value);
        }

        return Model::transaction(function() use ($id) {
            return Model::deleteBatch($id, 
                function($query, $event, $cursor) {
                    $cursor->update(['status' => false]);
            });
        });
    }

    public function restore(array $data) {
        return Model::transaction(function () use ($data) {
            return Model::restoreData($data['id'], 'id', function ($query) {
                $query->update(['status' => true]);
            });
        });
    }

    public static function dropdown($default = '')
    {
        $results = [];
        if (!is_null($default)) {
            $results[''] = empty($default) ? __('Pilih') : __($default);
        }

        $cursors = Model::isActive()->get();

        foreach ($cursors as $cursor) {
            $results[$cursor->id] = $cursor->name;
        }

        return $results;
    }

    public static function dropdownByCode($default = '')
    {
        $results = [];
        if (!is_null($default)) {
            $results[''] = empty($default) ? __('Pilih') : __($default);
        }

        $cursors = Model::isActive()->get();

        foreach ($cursors as $cursor) {
            $results[$cursor->code] = $cursor->name;
        }

        return $results;
    }

    public static function dropdownByCodeWithOtoritas($default = '')
    {
        $view_all_unit = auth()->user()->view_all_unit;
        $results = [];
        if (!is_null($default)) {
            $results[''] = empty($default) ? __('Pilih') : __($default);
        }

        if ($view_all_unit) {
            $cursors = Model::isActive()->get();
        } else {
            $tipe_unit_kerja_code = auth()->user()->unitkerja->tipeunitkerja->code;
            $cursors = Model::isActive()->where('code', $tipe_unit_kerja_code)->orderBy('name', 'asc')->get();
        }

        foreach ($cursors as $cursor) {
            $results[$cursor->code] = $cursor->name;
        }

        return $results;
    }

    public static function dropdownAttributes(){
        
        $results = [];
        $results[''] = ['data-code' =>  '' ];

        $cursors = Model::isActive()->get();
        foreach ($cursors as $cursor) {
        $results[$cursor->id] = ['data-code' => $cursor->code];
        }
        
        return $results;
    }

    public static function getTipeUnitKerjaId(){
        $results = [];

        $cursors = Model::isActive()->get();
        foreach ($cursors as $cursor) {
            $results[$cursor->code] = $cursor->id;
        }

        return $results;
    }
}
