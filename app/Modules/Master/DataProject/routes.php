<?php

use Illuminate\Support\Facades\Route;

// Export routes (harus sebelum resource routes)
Route::get('dataproject/export/pdf', '\App\Modules\Master\DataProject\Controllers\Controller@exportPdf')->name('dataproject.export.pdf');
Route::get('dataproject/export/excel', '\App\Modules\Master\DataProject\Controllers\Controller@exportExcel')->name('dataproject.export.excel');
Route::get('dataproject/export/word', '\App\Modules\Master\DataProject\Controllers\Controller@exportWord')->name('dataproject.export.word');

// Route untuk data (harus sebelum resource routes)
Route::get('dataproject/data', '\App\Modules\Master\DataProject\Controllers\Controller@data')->name('dataproject.data');
Route::get('dataproject/download', '\App\Modules\Master\DataProject\Controllers\Controller@download')->name('dataproject.download');
Route::get('dataproject/import', '\App\Modules\Master\DataProject\Controllers\Controller@import')->name('dataproject.import');
Route::post('dataproject/import', '\App\Modules\Master\DataProject\Controllers\Controller@importPost')->name('dataproject.import.post');
Route::post('dataproject/destroys', '\App\Modules\Master\DataProject\Controllers\Controller@destroys')->name('dataproject.destroys');

// Resource routes (harus di akhir)
Route::resource('dataproject', '\App\Modules\Master\DataProject\Controllers\Controller', [
    'names' => 'dataproject'
]);
