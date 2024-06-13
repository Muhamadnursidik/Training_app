<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Role\Permission;

class Acl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acl:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permission from routes name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('');
        $this->info('[ Generate Permission ]');
        if ($this->confirm('Do you wish to continue?')) {

            $routeCollection = Route::getRoutes();
            $bar = $this->output->createProgressBar(count($routeCollection));
            $bar->start();
            $counter = ['new' => 0, 'skip' => 0, 'exist' => 0];
            foreach ($routeCollection as $route) {
                $route_name = $route->getName();

                if (empty($route_name)) {
                    $status = '[SKIP]';
                    $counter['skip'] += 1;
                } else {
                    $exist = Permission::where('name', $route_name)->count();
                    if ($exist) {
                        $status = '[EXIST]';
                        $counter['exist'] += 1;
                    } else {
                        Permission::create(['name' => $route_name]);
                        $status = '[NEW]';
                        $counter['new'] += 1;
                    }
                }

                $this->info(' ' . $status . ' ' . $route_name);
                $bar->advance();
            }
            $bar->finish();

            $this->info(' [Done] ' . $counter['new'] . ' New, ' . $counter['skip'] . ' Skip, ' . $counter['exist'] . ' Exist');
        }

    }
}
