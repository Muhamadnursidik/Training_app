<?php

use Illuminate\Support\Facades\Route;

Route::resource('dataliburnasional', \App\Modules\Master\Dataliburnasional\Controller::class, [
    'names' => 'dataliburnasional',
    'except' => ['show'] // lamun teu dipake
]);

// tambahan custom route
Route::get('dataliburnasional/data', [\App\Modules\Master\Dataliburnasional\Controller::class, 'data'])
    ->name('dataliburnasional.data');

Route::get('dataliburnasional/import', [\App\Modules\Master\Dataliburnasional\Controller::class, 'import'])
    ->name('dataliburnasional.import');

Route::post('dataliburnasional/import', [\App\Modules\Master\Dataliburnasional\Controller::class, 'processImport'])
    ->name('dataliburnasional.process-import');

Route::get('dataliburnasional/export/{type?}', [\App\Modules\Master\Dataliburnasional\Controller::class, 'export'])
    ->name('dataliburnasional.export');

Route::get('dataliburnasional/generatedropdownbytipe', [\App\Modules\Master\Dataliburnasional\Controller::class, 'generatedropdownbytipe'])
    ->name('dataliburnasional.generatedropdownbytipe');

Route::get('dataliburnasional/generatedropdownbagian', [\App\Modules\Master\Dataliburnasional\Controller::class, 'generatedropdownbagian'])
    ->name('dataliburnasional.generatedropdownbagian');
