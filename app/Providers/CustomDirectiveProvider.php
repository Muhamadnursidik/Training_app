<?php

namespace App\Providers;

use App\Facades\Common;
use Blade;
use Illuminate\Support\ServiceProvider;

class CustomDirectiveProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::directive('priority_color', function($priority){
            return "{{ \App\Helpers\Common::priority_color($priority) }}";
        });

        Blade::directive('priority_status', function($priority){
            return "{{ \App\Helpers\Common::priority_status($priority) }}";
        });

        Blade::directive('coaching_color_status', function($status){
            return "{{ \App\Helpers\Common::status_color($status) }}";
        });

        Blade::directive('coaching_label_status', function($status){
            return "{{ \App\Helpers\Common::status_label($status) }}";
        });

        Blade::directive('format_date', function($date){
            return "{{ \Carbon\Carbon::parse($date)->isoFormat('DD MMM YYYY') }}";
        });

        Blade::directive('first_letter', function($string){
            return "{{ \App\Helpers\Common::get_first_letter($string) }}";
        });

        Blade::directive('format_number', function($string){
            return "{{ \App\Helpers\Common::formatNumber($string, '') }}";
        });
    }
}
