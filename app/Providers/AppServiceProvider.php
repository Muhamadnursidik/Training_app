<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Client;
use Laravel\Passport\PersonalAccessClient;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use Jenssegers\Agent\Agent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('common_helper', function(){ return new \App\Helpers\Common; });
        $this->app->bind('menu_helper', function(){ return new \App\Helpers\Menu; });
        $this->app->bind('form_helper', function () { return new \App\Helpers\Form; });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        
        Schema::defaultStringLength(191);
        
        /*tambah ip address*/
        $agent = new Agent();
        
        Activity::saving(function (Activity $activity) use($agent){
            $activity->properties = $activity->properties
            ->put('ip_address', request()->ip())
            ->put('os', $agent->platform())
            ->put('browser', $agent->browser())
            ->put('device', $agent->device())
            ;
        });

        /* Begin : UUID Adjustment */
        Client::creating(function (Client $client) {
            $client->incrementing = false;
            $client->id = Uuid::uuid4()->toString();
        });

        PersonalAccessClient::creating(function (PersonalAccessClient $personal_access_client) {
            $personal_access_client->incrementing = false;
            $personal_access_client->id = Uuid::uuid4()->toString();
        });

        Permission::retrieved(function (Permission $permission) {
            $permission->incrementing = false;
        });

        Permission::creating(function (Permission $permission) {
            $permission->incrementing = false;
            $permission->id = Uuid::uuid4()->toString();
        });

        Role::retrieved(function (Role $role) {
            $role->incrementing = false;
        });

        Role::creating(function (Role $role) {
            $role->incrementing = false;
            $role->id = Uuid::uuid4()->toString();
        });
        /* End : UUID Adjustment */

        $registrar = new \App\Core\ResourceRegistrar($this->app['router']);

        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function () use ($registrar) {
            return $registrar;
        });
        
    }
}
