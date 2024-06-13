<?php
Route::resource('appconfig', '\App\Modules\Tools\Appconfig\Controller', [
    'names' => 'appconfig'
])->middleware(['unlock_appconfig']);


Route::get('appconfig/unlock',
'\App\Modules\Tools\Appconfig\Controller@unlock')->name('appconfig.unlock');
Route::post('appconfig/unlockconfig',
'\App\Modules\Tools\Appconfig\Controller@unlockconfig')->name('appconfig.unlockconfig');