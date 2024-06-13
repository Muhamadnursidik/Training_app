<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Admin\Menu\Model as MenuModel;
class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->line("Make Menus...");
        $menus = [
            [
                'name' => 'Beranda',
                'custom_url' => 0,
                'url' => 'home',
                'icon' => 'fas fa-home',
                'data_authority' => 0,
                'category' => 'admin'
            ],
            [
                'name' => 'Master Data',
                'custom_url' => 1,
                'url' => '#',
                'icon' => 'fas fa-cogs',
                'data_authority' => NULL,
                'category' => 'admin'
            ],
            [
                'name' => 'Pengaturan',
                'custom_url' => 1,
                'url' => '#',
                'icon' => 'fas fa-cogs',
                'data_authority' => NULL,
                'category' => 'admin',
                'childrens' => [
                    [
                        'name' => 'Menu',
                        'custom_url' => 0,
                        'url' => 'admin.menu.index',
                        'data_authority' => 0,
                        'category' => 'admin'
                    ],
                    [
                        'name' => 'Hak Akses',
                        'custom_url' => 0,
                        'url' => 'admin.role.index',
                        'data_authority' => 0,
                        'category' => 'admin'
                    ],
                    [
                        'name' => 'User',
                        'custom_url' => 0,
                        'url' => 'admin.user.index',
                        'data_authority' => 0,
                        'category' => 'admin'
                    ]
                ]
            ]
        ];

        $this->processMenu($menus);

    }

    private function processMenu(array $menus, $parent_id = NULL)
    {
        $sequence = 0;
        foreach ($menus as $menu) {
            $sequence++;
            $menu['parent_id'] = $parent_id;
            $menu['sequence'] = $sequence;

            $role = MenuModel::where('name', $menu['name'])->where('url', $menu['url'])->first();
            $childrens = isset($menu['childrens']) ? $menu['childrens'] : NULL;
            unset($menu['childrens']);

            if (!$role) {
                $role = MenuModel::create($menu);
            } else {
                $role->update($menu);
            }

            if ($childrens) {
                $this->processMenu($childrens, $role->id);
            }
        }
    }
}
