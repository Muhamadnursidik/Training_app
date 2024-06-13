<?php
Route::resource('unitkerja', '\App\Modules\Master\Unitkerja\Controller', [
    'names' => 'unitkerja'
]);

Route::get('unitkerja/generatedropdownbytipe', '\App\Modules\Master\Unitkerja\Controller@generatedropdownbytipe')->name('unitkerja.generatedropdownbytipe');
Route::get('unitkerja/generatedropdownbagian', '\App\Modules\Master\Unitkerja\Controller@generatedropdownbagian')->name('unitkerja.generatedropdownbagian');
Route::get('unitkerja/download', '\App\Modules\Master\Unitkerja\Controller@download')->name('unitkerja.download');
Route::get('unitkerja/import', '\App\Modules\Master\Unitkerja\Controller@import')->name('unitkerja.import');
Route::post('unitkerja/import', '\App\Modules\Master\Unitkerja\Controller@importPost')->name('unitkerja.import');