<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use App\Modules\Tools\Appconfig\Service;

class AppConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            /* get all config */
            $config = Service::getAll();

            if ($config) {
                foreach ($config as $conf) {
                    $configdata = json_decode($conf->config, true);
                    $append_config = [];
                    foreach ($configdata as $key => $item) {
                        if ($conf->code == 'MAIL') {
                            if (in_array($item['key'], ['address', 'name'])) {
                                $append_config['from'][$item['key']] = $item['value'];
                            } else {
                                $append_config[$item['key']] = $item['value'];
                            }
                        } else {
                            $append_config[$item['key']] = $item['value'];
                        }
                    }

                    Config::set(strtolower($conf->code), $append_config);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
