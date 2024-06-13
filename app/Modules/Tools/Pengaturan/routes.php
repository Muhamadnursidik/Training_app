<?php
Route::resource('pengaturan', '\App\Modules\Tools\Pengaturan\Controller', [
    'names' => 'pengaturan'
]);

Route::get('pengaturan/data', '\App\Modules\Tools\Pengaturan\Controller@page')->name('pengaturan.page');