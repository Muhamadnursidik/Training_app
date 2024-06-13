<?php
namespace App\Bases;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Menu\Service as MenuService;
use Exception;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Menu\Model as ModelMenu;
use Illuminate\Support\Facades\Auth;

class BaseModule extends Controller
{
    protected $module;
    protected $pageTitle;
    protected $pageSubTitle;
    protected $breadcrumb;
    protected $currentUrl = [];

    public function __construct()
    {
        $breadcrumb = $this->breadCrumb();

        $this->pageTitle = $breadcrumb['title'];
        $this->breadcrumb = $breadcrumb['breadcrumb'];
        $this->currentUrl = $breadcrumb['currenturl'];
    }
    protected function serveJSON($data, $code = 200, $status = 'success', $message = 'OK')
    {
        $output = $data;

        if (is_array($data)) {
            $code = isset($data['code']) ? $data['code'] : $code;

            $output = [
                'code' => $code,
                'status' => isset($data['status']) ? $data['status'] : $status,
                'message' => isset($data['message']) ? $data['message'] : $message,
                'data' => isset($data['data']) ? $data['data'] : NULL,
            ];

            // extend data table responses
            if (isset($data['draw'])) {
                $output['draw'] = $data['draw'];
            }
            if (isset($data['recordsTotal'])) {
                $output['recordsTotal'] = $data['recordsTotal'];
            }
            if (isset($data['recordsFiltered'])) {
                $output['recordsFiltered'] = $data['recordsFiltered'];
            }
        }

        return response()->json($output, $code);
    }

    protected function getMethodName()
    {
        return request()->route()->getActionMethod();
    }

    protected function getRouteName()
    {
        return request()->route()->getName();
    }

    protected function getModuleName()
    {
        return $this->module;
    }

    protected function getViewPath()
    {
        return str_replace('.', DIRECTORY_SEPARATOR, $this->getModuleName()) . '::' . $this->getMethodName();
    }

    protected function getRouteGroup()
    {
        return request()->getHost() == config('domain.admin') ? 'admin.' : 'app.';
    }

    protected function getUserType()
    {
        return request()->getHost() == config('domain.admin') ? 1 : 2;
    }

    protected function actions_permission()
    {
        $currentMenu = explode('.', $this->getRouteName());
        array_pop($currentMenu);
        $currentMenu[] = 'index';

        $currMenu = implode('.', $currentMenu);
        $getMenu = ModelMenu::where('url', $currMenu)->first();
        $permission = isset(Auth::user()->roles[0]->actions_permission) ? json_decode(Auth::user()->roles[0]->actions_permission, true) : [];

        foreach ($permission as $key => $perm) {
            if (isset($getMenu->id)) {
                if ($getMenu->id == $key) {
                    return $perm;
                }
            }
        }

        return [];
    }

    protected function serveView($data = [], $path = '', $return = false, $slice = true)
    {

        $breadcrumb = $this->breadCrumb();
        
        view()->share([
            'route_group' => $this->getRouteGroup(),
            'module' => $this->getModuleName(),
            'pageTitle' => $this->pageTitle,
            'breadcrumb' => $this->breadcrumb,
            'pageSubTitle' => '',
            'currentUrl' => $this->currentUrl,
            'menus' => MenuService::generateMenu(),
            'actions' => $this->actions_permission()
        ]);

        if (empty($path)) {
            $path = $this->getViewPath();
        }
        // dd($path);
        $view = view($path, $data);

        if ($return) {
            return $view->render();
        }

        return $view;
    }

    protected function breadcrumb()
    {

        if ($this->getModuleName()) {
        
            if (Route::getCurrentRoute()) {
                $explode = explode('.', Route::getCurrentRoute()->getName());
                $index0 = $explode[0] . '.' . $this->getModuleName();
                $index1 = $this->getModuleName() . '.index';
                $index2 = $explode[0] . '.' . $this->getModuleName() . '.index';
                $currentUrl = [$index0, $index1, $index2, $this->getRouteName()];
            }else{
                $currentUrl = ['home'];
            }

        } else {
            $currentUrl = ['home'];
        }

        $menu = MenuService::getMenuByUrl($currentUrl);

        if ($menu) {
            return ['breadcrumb' => [$menu->parent_name, $menu->name], 'title' => $menu->name, 'currenturl' => $currentUrl];
        } else {
            return ['breadcrumb' => [__('Beranda')], 'title' => __('Beranda'), 'currenturl' => $currentUrl];
        }
    }

    public function permissions($x)
    {
        /* if (!auth()->user()->can($x)) {
            abort(403);
        } */
    }
}
