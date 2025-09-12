<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Master\Rencanaproject\Controller\Controller;

// Resource utama
Route::resource('rencanaproject', Controller::class, [
    'names' => 'rencanaproject',
]);

// Custom route tambahan
Route::get('rencanaproject/data', [Controller::class, 'data'])
    ->name('rencanaproject.data');

Route::get('rencanaproject/import', [Controller::class, 'import'])
    ->name('rencanaproject.import');

Route::post('rencanaproject/import', [Controller::class, 'processImport'])
    ->name('rencanaproject.process-import');

Route::get('rencanaproject/export/{type?}', [Controller::class, 'export'])
    ->name('rencanaproject.export');

// Restore route
Route::patch('rencanaproject/{id}/restore', [Controller::class, 'restore'])
    ->name('rencanaproject.restore');

// Bulk operations
Route::delete('rencanaproject/bulk', [Controller::class, 'destroys'])
    ->name('rencanaproject.destroys');

// Dropdown generators
Route::get('rencanaproject/generatedropdownaktivitas', [Controller::class, 'generatedropdownaktivitas'])
    ->name('rencanaproject.generatedropdownaktivitas');

Route::get('rencanaproject/generatedropdownparent', [Controller::class, 'generatedropdownparent'])
    ->name('rencanaproject.generatedropdownparent');