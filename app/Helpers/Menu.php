<?php
namespace App\Helpers;

use App\Modules\Admin\Menu\Model;
use Auth;

class Menu
{
    public function generate($category)
    {
        $cursors = Model::where('category', $category)
            ->whereHas('roles', function ($q) {
                return $q->where('name', Auth::user()->getRoleNames());
            })
            ->orderBy('sequence')->get();
        $menus = [];

        $current_path = request()->path();
        $current_url = request()->route()->getName();

        $current_menu = null;
        $temp_menu = [];

        foreach ($cursors as $cursor) {
            // register menu
            $parent_id = !empty($cursor->parent_id) ? $cursor->parent_id : 0;
            $menus[$parent_id][] = $cursor;
            $temp_menu[$cursor->id] = $cursor->parent_id;

            // set active menu
            if ($current_path == $cursor->url || $current_url == $cursor->url) {
                $current_menu = $cursor->id;
            }
        }

        // setup active menu
        $active_menu = $this->setActiveMenu($current_menu, $temp_menu);

        return $this->parsingMenu($menus, 0, $active_menu);
    }

    private function parsingMenu(array $menus, $parent_id = 0, $active_menu, $ul_class = 'nav')
    {
        $results = '<ul class="' . $ul_class . '">';
        $results .= '<li class="nav-item nav-category">Navigasi Menu</li>';
        if (isset($menus[$parent_id])) {
            foreach ($menus[$parent_id] as $menu) {
                try {
                    $url = $menu->custom_url ? url($menu->url) : route($menu->url);
                }catch(\Exception $e){
                    $url = '';
                }


                $active_class = '';
                $show_class = '';
                $arrow_position = 'false';
                if (in_array($menu->id, $active_menu)) {
                    $active_class = 'active';
                    $show_class = 'show';
                    $arrow_position = 'true';
                }

                $treeview = '';
                $a_attr = 'class="nav-link" href="' . $url . '"';
                $children = '';
                $menuName = str_replace(' ', '-', $menu->name);
                $anchor = '';

                if (isset($menus[$menu->id])) {
                    $children = $this->parsingChildMenu($menus, $menu->id, $active_menu, $menuName, $show_class);
                    $treeview = '';
                    $a_attr = 'class="nav-link" href="#' . $menuName . '" data-toggle="collapse" aria-expanded="' . $arrow_position . '"';
                    $anchor = '<i class="link-arrow fas fa-angle-up"></i>';
                }

                $icon = !empty($menu->icon) ? '<i class="' . $menu->icon . '"></i>' : '';

                $nav = '<li class="nav-item ' . $active_class . '">';
                $nav .= '<a ' . $a_attr . ' >';
                $nav .= $icon . '<span class="link-title">' . __($menu->name) . '</span>';
                $nav .= $anchor;
                $nav .= '</a>';
                $nav .= $children;
                $nav .= '</li>';

                $results .= $nav;
            }
        }


        $results .= '</ul>';

        return $results;
    }

    private function parsingChildMenu(array $menus, $parent_id = 0, $active_menu, $ul_class = '', $show_class)
    {
        $results = '<div class="collapse ' . $show_class . '" id="' . $ul_class . '">';
        $results .= '<ul class="nav sub-menu">';
        if (isset($menus[$parent_id])) {
            foreach ($menus[$parent_id] as $menu) {
                try{
                    $url = $menu->custom_url ? url($menu->url) : route($menu->url);
                }catch(\Exception $e){
                    $url = '';
                }

                $active_class = '';
                $show_class = '';
                $arrow_position = 'false';
                if (in_array($menu->id, $active_menu)) {
                    $active_class = 'active';
                    $show_class = 'show';
                    $arrow_position = 'true';
                }

                $treeview = 'nav-item ' . $active_class;
                $a_attr = 'class="nav-link ' . $active_class . '" href="' . $url . '"';
                $children = '';
                $menuName = str_replace(' ', '-', $menu->name);
                $anchor = '';

                if (isset($menus[$menu->id])) {
                    $children = $this->parsingChildMenu($menus, $menu->id, $active_menu, $menuName, $show_class);
                    $treeview = 'nav-item';
                    $a_attr = 'class="nav-link ' . $active_class . '" aria-expanded="' . $arrow_position . '" href="#' . $menuName . '" data-toggle="collapse" ';
                    $anchor = '<i class="link-arrow fas fa-angle-up"></i>';
                }

                $icon = !empty($menu->icon) ? '<i class="' . $menu->icon . '"></i>' : '';

                $nav = '<li class="' . $treeview . '">';
                $nav .= '<a ' . $a_attr . '>';
                $nav .= $icon . ' <span>' . __($menu->name) . '</span>';
                $nav .= $anchor;
                $nav .= '</a>';
                $nav .= $children;
                $nav .= '</li>';

                $results .= $nav;
            }
        }

        $results .= '</ul>';
        $results .= '</div>';

        return $results;
    }

    private function getName($url)
    {
        return app('router')->getRoutes()->match(app('request')->create($url))->getName();
    }

    private function setActiveMenu($current_menu, $temp_menu, $active_menu = [])
    {
        if (!empty($current_menu)) {
            $active_menu[] = $current_menu;
            if (!empty($temp_menu[$current_menu])) {
                $active_menu = $this->setActiveMenu($temp_menu[$current_menu], $temp_menu, $active_menu);
            }
        }

        return $active_menu;
    }
}
