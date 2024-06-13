<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Admin\Role\Role;
use App\Modules\Admin\Role\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Modules\Admin\Menu\Model as MenuModel;
use App\Modules\Admin\Role\Model as RoleModel;
use App\Modules\Master\Unitkerja\Model as UnitModel;
use App\Modules\Master\Tipeunitkerja\Model as TipeUnitModel;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::where('name', 'Superadmin')->first();
        if (!$role) {
            $this->command->line("Make Default Role...");
            $role = Role::create(['name' => 'Superadmin', 'status' => 1]);
        }

        $TipeUnitModel = TipeUnitModel::where('code', 'K')->first();
        if (!$TipeUnitModel) {
            $this->command->line("Make Default Tipe Unit...");
            $TipeUnit = TipeUnitModel::create(['name' => 'Konsolidasi', 'status' => true, 'code' => 'K']);
        }

        $UnitModel = UnitModel::where('code', 'KON')->first();
        if (!$UnitModel) {
            $this->command->line("Make Default Unit Kerja...");
            $Unit = UnitModel::create(['name' => 'Kantor Pusat', 'status' => true, 'code' => 'KON']);
        }

        $this->command->line("Regsiter All Permission to Default Role...");
        $role->givePermissionTo(Permission::all());

        $this->command->line("Regsiter All Menus to Default Role...");
        RoleModel::find($role->id)->menus()->sync(MenuModel::pluck('id')->toArray());

        $this->command->line("");
        $this->command->line("Create Default User...");
        $user = User::where('email', 'admin@bartechmedia.id')->first();
        $dataUser = [
            'name'  => "Administrator",
            'email' => 'admin@bartechmedia.id',
            'username' => 'admin',
            'password'  => Hash::make('Admin@123'),
            'unit_kerja_code' => 'KON',
            'type' => 1,
            'status' => 1,
            'is_admin' => true,
            'email_verified_at' => now()
        ];

        if (!$user) {
            $user = User::create($dataUser);
        } else {
            $user->update($dataUser);
        }

        $this->command->line(" + Email: " .  $dataUser['email']);
        $this->command->line(" + Username: " .  $dataUser['username']);
        $this->command->line(" + Password: Admin@123");
        $this->command->line("");

        $this->command->line("Assign Superadmin to Default User...");
        $user->assignRole('Superadmin');
    }
}
