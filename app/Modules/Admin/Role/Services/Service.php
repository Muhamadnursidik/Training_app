<?php
namespace App\Modules\Admin\Role;

use App\Bases\BaseService;
use DataTables;
use App\Modules\Admin\Role\Model;
use App\Modules\Admin\Menu\Model as MenuModel;
use App\Modules\Admin\Menu\Service as MenuService;
use App\Modules\Tools\Upload\Service as UploadService;
use Illuminate\Support\Facades\Cache;
use DB;
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
                if ($data['name'] != '')
                    $query->whereLike('name', $data['name']);

                if ($data['status'] != '')
                    $query->where('status', $data['status']);
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
                'name'        => $data['name'],
                'guard_name'  => $data['guard_name'],
                'description' => $data['description'],
                'status'      => $data['status'] ? 1 : 0,
            ], function($query, $event) use ($data){
                $event->permissions()->attach(is_array($data['permissions']) ? $data['permissions'] : []);
                $event->menus()->attach(is_array($data['visibilities']) ? $data['visibilities'] : []);
                
                // Upload
                $data['source_id'] = $event->id;
                $data['module'] = 'role';
                UploadService::uploadSingle($data);

                app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

                Cache::flush();
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

    public function update(array $data) {
        return Model::transaction(function() use ($data) {
            return Model::updateOne($data['id'], [
                'name'        => $data['name'],
                'description' => $data['description'],
                'status'      => $data['status']  ? 1 : 0,
            ], function($query, $event, $cursor) use ($data){
                $cursor->permissions()->sync(is_array($data['permissions']) ? $data['permissions'] : []);
                $cursor->menus()->sync(is_array($data['visibilities']) ? $data['visibilities'] : []);
                
                app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
                // Upload
                $data['source_id'] = $cursor->id;
                $data['module'] = 'role';
                UploadService::uploadSingle($data);

                Cache::flush();
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

    public function getMenus(array $data)
    {
        $cursors = MenuModel::orderBy('sequence')->get();
        $menus = [];

        foreach ($cursors as $cursor) {
            $parent_id = !empty($cursor->parent_id) ? $cursor->parent_id : 0;
            $menus[$parent_id][] = $cursor;
        }

        $results = count($menus) > 0 ? $this->parsingMenu($menus) : [];
        return $this->outputResult($results);
    }

    private function parsingMenu(array $menus, $parent_id = 0)
    {
        $results = [];
        foreach ($menus[$parent_id] as $menu) {
            try{
                $url = $menu->custom_url ? $menu->url : route($menu->url);
            }catch(\Exception $e){
                $url ='';
            }
            $data = [
                'id'          => $menu->id,
                'label'       => $menu->name,
                'url'         => $url,
                'icon'        => $menu->icon,
                'permissions' => MenuService::hasPermissions($menu->id)
            ];

            if (isset($menus[$menu->id])) {
                $data['children'] = $this->parsingMenu($menus, $menu->id);
            }

            $results[] = $data;
        }

        return $results;
    }

    public static function hasPermissions($id)
    {
        $menu = Model::find($id);
        $permissions = [];
        if ($menu) {
            foreach ($menu->permissions->sortBy(function($q){
                return $q->pivot->sequence;
            }) as $permission) {
                $pivot = $permission->pivot;
                $permissions[] = $permission->id;
            }
        }
        return $permissions;
    }

    public static function hasVisibilities($id)
    {
        $menu = Model::find($id);
        $visibilities = [];
        if ($menu) {
            foreach ($menu->menus as $menu) {
                $pivot = $menu->pivot;
                $visibilities[] = $menu->id;
            }
        }
        return $visibilities;
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

    public function mappingstore(array $data)
    {
        return DB::transaction(function () use ($data) {

            DB::table('mapping_role_jabatan_bagian')->where('role_id', $data['id'])->delete();

            foreach ($data['mapping'] as $key => $value) {
                DB::table('mapping_role_jabatan_bagian')->insert([
                    'role_id' => $data['id'],
                    'jabatan_code' => $value['jabatan_code'],
                    'bagian_code' => json_encode($value['bagian_code']),
                    'unit_kerja_code' => json_encode($value['unit_kerja_code']),
                ]);
            }

            return true;
        });
    }

    public static function mappingData($id){
        $find = DB::table('mapping_role_jabatan_bagian')->where('role_id', $id)->get();
        return $find->toArray();
    }
    public static function mappingReport($id)
    {
        $query = Model::select([
                'roles.name as name_role',
                'c.name as bagian_name',
                'd.name as jabatan_name',
                'e.name as unit_kerja_name',
                'mapping.bagian as bagian_code',
                'mapping_unit_kerja.unit_kerja as unit_kerja_code'
                // 'mapping.role_id'
            ])
        ->join(DB::raw('(SELECT role_id,
                        jabatan_code,
                        json_array_elements_text(bagian_code)::text as bagian from mapping_role_jabatan_bagian)
                        AS mapping'),
        function ($query){
            $query->on('mapping.role_id', 'roles.id');
        })
        ->join(DB::raw('(SELECT role_id,
                        json_array_elements_text(unit_kerja_code)::text as unit_kerja from mapping_role_jabatan_bagian)
                        AS mapping_unit_kerja'),
        function ($query){
            $query->on('mapping_unit_kerja.role_id', 'roles.id');
        })
        ->join('master_bagian as c', function ($query){
            $query->on('c.code', 'mapping.bagian');
        })
        ->join('master_jabatan as d', function ($query){
            $query->on('d.code', 'mapping.jabatan_code');
        })
        ->join('master_unit_kerja as e', function ($query){
            $query->on('e.code', 'mapping_unit_kerja.unit_kerja');
        })
        ->where(function ($query) use ($id){
            $query->where('roles.id', $id);
        })->get();
        $result = [];
        foreach ($query as $key => $value) {
            $result[$value->jabatan_name]['bagian'][$value->bagian_code] = $value->bagian_name;
            $result[$value->jabatan_name]['unit_kerja'][$value->unit_kerja_code] = $value->unit_kerja_name;
        }
        return $result;
    }

    public static function getMapping(array $data){
        $find = $find = DB::table('mapping_role_jabatan_bagian','a')
        ->select(['b.id as role_id','b.name'])
        ->join('roles as b', function($query){
            $query->on('b.id','=','a.role_id');
        })
        ->where(function($query)use($data){
            $query->where('a.jabatan_code', $data['jabatan']);
            $query->whereJsonContains('a.bagian_code', $data['kodebagian']);
            $query->whereJsonContains('a.unit_kerja_code', $data['nopend']);

        })->first();

        return !empty($find) ? $find : null;
    }
    
    public function duplicate(array $data)
    {

        $role = Model::find($data['id']);
        
        $data = [
            'name'          => 'HASIL DUPLIKASI DARI ROLE : '.$role->name,
            'guard_name'    => $role->guard_name,
            'description'   => $role->description,
            'status'        => $role->status
        ];

        $data['permissions'] = self::hasPermissions($role->id);
        $data['visibilities'] = self::hasVisibilities($role->id);
        
        return $this->store($data);
    }
}
