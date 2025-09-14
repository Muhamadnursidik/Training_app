<?php

use Illuminate\Support\Facades\Route;

// Export routes (harus sebelum resource routes)
Route::get('datamitra/export/pdf', '\App\Modules\Master\DataMitra\Controllers\Controller@exportPdf')->name('datamitra.export.pdf');
Route::get('datamitra/export/excel', '\App\Modules\Master\DataMitra\Controllers\Controller@exportExcel')->name('datamitra.export.excel');
Route::get('datamitra/export/word', '\App\Modules\Master\DataMitra\Controllers\Controller@exportWord')->name('datamitra.export.word');

// Route untuk data (harus sebelum resource routes)
Route::get('datamitra/data', '\App\Modules\Master\DataMitra\Controllers\Controller@data')->name('datamitra.data');
Route::get('datamitra/download', '\App\Modules\Master\DataMitra\Controllers\Controller@download')->name('datamitra.download');
Route::get('datamitra/import', '\App\Modules\Master\DataMitra\Controllers\Controller@import')->name('datamitra.import');
Route::post('datamitra/import', '\App\Modules\Master\DataMitra\Controllers\Controller@importPost')->name('datamitra.import.post');
Route::post('datamitra/destroys', '\App\Modules\Master\DataMitra\Controllers\Controller@destroys')->name('datamitra.destroys');
Route::post('datamitra/restore/{id}', '\App\Modules\Master\DataMitra\Controllers\Controller@restore')->name('datamitra.restore');

// Resource routes (harus di akhir)
Route::resource('datamitra', '\App\Modules\Master\DataMitra\Controllers\Controller', [
    'names' => 'datamitra'
]);
