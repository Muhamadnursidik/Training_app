<?php
Route::resource('role', '\App\Modules\Admin\Role\Controller', [
    'names' => 'role'
]);

Route::get('role/menus', '\App\Modules\Admin\Role\Controller@getMenus')->name('role.menus');
Route::get('role/{id}/mapping', '\App\Modules\Admin\Role\Controller@mapping')->name('role.mapping');
Route::put('role/{id}/mappingstore', '\App\Modules\Admin\Role\Controller@mappingstore')->name('role.mappingstore');
Route::get('role/{id}/report',
'\App\Modules\Admin\Role\Controller@report')->name('role.report');
Route::get('role/{id}/duplicate', '\App\Modules\Admin\Role\Controller@duplicate')->name('role.duplicate');