<?php

namespace App\Modules\Admin\Menu;

use App\Bases\BaseService;
use App\Modules\Admin\Menu\Model;
use Spatie\Permission\Models\Permission;
use DataTables;
use Route;
use Config;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Service extends BaseService
{

    public function __construct()
    {
    }

    public function data(array $data)
    {
        $cursors = Model::orderBy('sequence')->get();
        $menus = [];

        foreach ($cursors as $cursor) {
            $parent_id = !empty($cursor->parent_id) ? $cursor->parent_id : 0;
            $menus[$parent_id][] = $cursor;
        }

        $results = count($menus) > 0 ? self::parsingMenu($menus) : [];
        return $this->outputResult($results);
    }

    private static function parsingMenu(array $menus, $parent_id = 0, $route = true)
    {
        $results = [];
        if (count($menus) > 0) {
            foreach ($menus[$parent_id] as $menu) {
                try {
                    $url = $menu->custom_url ? $menu->url : (($route) ? route($menu->url) : $menu->url);
                } catch (Exception $e) {
                    $url = '#';
                }

                $data = [
                    'id' => encrypt($menu->id),
                    'label' => $menu->name,
                    'url' => $url,
                    'icon' => $menu->icon,
                    'slug' => $menu->url
                ];

                if (isset($menus[$menu->id])) {
                    $data['children'] = self::parsingMenu($menus, $menu->id);
                }

                $results[] = $data;
            }
        }
        return $results;
    }

    public function store(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::createOne([
                'custom_url' => $data['custom_url'] ? 1 : 0,
                'name' => $data['name'],
                'url' => $data['custom_url'] ? $data['url'] : $data['route'],
                'icon' => $data['icon'],
                'description' => $data['description'],
                'category' => $data['category'],
                'parent_id' => !empty($data['parent_id']) ? decrypt($data['parent_id']) : NULL,
                'data_authority' => $data['data_authority'] ? 1 : 0
            ], function ($query, $event) use ($data) {
                $event->permissions()->attach(is_array($data['features']) ? $data['features'] : []);
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

    public function update(array $data)
    {

        return Model::transaction(function () use ($data) {
            return Model::updateOne($data['id'], [
                'custom_url' => $data['custom_url'] ? 1 : 0,
                'name' => $data['name'],
                'url' => $data['custom_url'] ? $data['url'] : $data['route'],
                'icon' => $data['icon'],
                'description' => $data['description'],
                'parent_id' => !empty($data['parent_id']) ? decrypt($data['parent_id']) : NULL,
                'data_authority' => $data['data_authority'] ? 1 : 0
            ], function ($query, $event, $cursor) use ($data) {
                $cursor->permissions()->detach();
                $cursor->permissions()->sync(is_array($data['features']) ? $data['features'] : []);

                Cache::flush();
            });
        });
    }

    public function destroy(array $data)
    {
        $query = Model::where('parent_id', $data['id'])->count();
        if ($query) {
            return $this->outputResult([], 422, __("Oops! Menu tidak dapat dihapus, karena memiliki turunan."));
        }

        return Model::deleteOne($data['id']);
    }

    public function destroys(array $data)
    {
        $id = [];
        foreach ($data['id'] as $value) {
            $id[] = decrypt($value);
        }

        return Model::transaction(function () use ($id) {
            return Model::deleteBatch($id);
        });
    }

    public static function getRoutesAdmin($default = null)
    {

        $routeCollection = Route::getRoutes();
        $routes = [];
        if (!empty($default)) {
            $routes = ['' => $default];
        }

        foreach ($routeCollection as $route) {
            $route_name = $route->getName();

            if (!empty($route_name)) {
                if (in_array('GET', $route->methods()) && count($route->parameterNames()) == 0) {
                    $routes[$route_name] = $route_name;
                }
            }
        }
        return $routes;
    }

    public static function getPermission($default = null)
    {

        $cursors = Permission::all();
        $permissions = [];
        if (!empty($default)) {
            $permissions = ['' => $default];
        }

        foreach ($cursors as $cursor) {
            $permissions[$cursor->id] = $cursor->name;
        }

        return $permissions;
    }

    public function saveOrder(array $data)
    {
        return Model::transaction(function () use ($data) {
            $sequences = json_decode($data['sequence']);

            if (is_array($sequences)) {
                $this->setMenu($sequences);
            }

            return $this->outputResult($sequences);
        });
    }

    public function setMenu($sequences, $parent_id = null)
    {
        foreach ($sequences as $key => $value) {
            $id = decrypt($value->id);
            Model::updateOne($id, [
                'sequence' => $key,
                'parent_id' => $parent_id
            ]);

            if (isset($value->children)) {
                $this->setMenu($value->children, $id);
            }
        }
    }

    public static function hasPermissions($id)
    {
        $menu = Model::find($id);
        $permissions = [];
        if (isset($menu->permissions) && count($menu->permissions)) {
            foreach ($menu->permissions->sortBy(function ($q) {
                return $q->pivot->sequence;
            }) as $permission) {
                $pivot = $permission->pivot;
                $permissions[] = [
                    'id' => $permission->id,
                    'name' => $pivot->name,
                ];
            }
        }
        return $permissions;
    }

    public static function generateMenu($category = 'admin')
    {

        $role_id = isset(Auth::user()->roles[0]) ? Auth::user()->roles[0]->id : null;
        $user_id = isset(Auth::user()->id) ? Auth::user()->id : null;
        $_menus = [];
        if ($role_id) {
            $cursors = Model::select(['name', 'id', 'parent_id', 'url', 'icon', 'custom_url'])->where('category', $category)
                ->whereHas('roles', function ($q) use ($role_id) {
                    return $q->where('id', $role_id);
                })
                ->orderBy('sequence', 'asc')->get();

            // $lifetime = config('session.lifetime');
            // $otoritas_menus = Cache::get($user_id);
            // if (empty($otoritas_menus)) {
            //     $cursors = Cache::remember($user_id, 10 * (int) $lifetime, function () use ($role_id, $category) {
            //         return Model::select(['name', 'id', 'parent_id', 'url', 'icon', 'custom_url'])->where('category', $category)
            //             ->whereHas('roles', function ($q) use ($role_id) {
            //                 return $q->where('id', $role_id);
            //             })
            //             ->orderBy('sequence', 'asc')->get();
            //     });
            // } else {
            //     $cursors = $otoritas_menus;
            // }

            $i = 0;

            foreach ($cursors as $menu) {
                $parent_id = !empty($menu->parent_id) ? $menu->parent_id : 0;
                $_menus[$parent_id][] = $menu;

                $i++;
            }

            $a = self::parsingMenu($_menus, 0, false);
            return $a;
        } else {
            return [];
        }
    }

    public static function getMenuByUrl(array $url)
    {

        $cursors = DB::table('menus', 'a')
            ->select([
                'a.id',
                'a.url',
                'a.name',
                'b.name as parent_name',
                'b.url as parent_url'
            ])
            ->leftJoin('menus as b', function ($q) use ($url) {
                $q->on('b.id', '=', 'a.parent_id');
            })
            ->whereIn('a.url', $url)
            ->first();

        return $cursors;
    }
}
