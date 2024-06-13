<?php
Route::resource('tools/activitylog', '\App\Modules\Tools\Activitylog\Controller', [
    'names' => 'tools.activitylog'
]);

Route::get(
    'tools/activitylog/detail',
    '\App\Modules\Tools\Activitylog\Controller@detail'
)->name('tools.activitylog.detail');


Route::get('tools/activitylog/export/', '\App\Modules\Tools\Activitylog\Controller@export')
    ->name('tools.activitylog.export');
