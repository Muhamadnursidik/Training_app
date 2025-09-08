<?php
Route::delete('/dataliburnasional/{id}', [App\Modules\Master\Dataliburnasional\Controller::class, 'destroy'])
    ->name('dataliburnasional.destroy');

Route::resource('dataliburnasional', 'App\Modules\Master\Dataliburnasional\Controller', [
    'names' => 'dataliburnasional',
    'except' => ['destroy']
]);

Route::get('dataliburnasional/generatedropdownbytipe', ['\App\Modules\Master\dataliburnasional\Controller@generatedropdownbytipe'])->name('dataliburnasional.generatedropdownbytipe');
Route::get('dataliburnasional/generatedropdownbagian', ['\App\Modules\Master\dataliburnasional\Controller@generatedropdownbagian'])->name('dataliburnasional.generatedropdownbagian');
Route::get('dataliburnasional/download', ['\App\Modules\Master\dataliburnasional\Controller@download'])->name('dataliburnasional.download');
Route::get('dataliburnasional/import', ['\App\Modules\Master\dataliburnasional\Controller@import'])->name('dataliburnasional.import');
Route::post('dataliburnasional/import', ['\App\Modules\Master\dataliburnasional\Controller@importPost'])->name('dataliburnasional.import');
