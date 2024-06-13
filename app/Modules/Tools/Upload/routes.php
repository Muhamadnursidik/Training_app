<?php
Route::resource('tools/upload', '\App\Modules\Tools\Upload\Controller', [
    'names' => 'tools.upload'
]);

Route::get('attachment/{id}/download',
'\App\Modules\Tools\Upload\Controller@download')->name('tools.upload.download');
