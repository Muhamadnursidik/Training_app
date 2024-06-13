<?php
Route::resource('menu', '\App\Modules\Admin\Menu\Controller', [
    'names' => 'menu'
]);

Route::put('menu/order/save', '\App\Modules\Admin\Menu\Controller@saveOrder')->name('menu.save-order');
