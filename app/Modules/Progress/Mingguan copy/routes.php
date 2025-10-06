<?php

use App\Modules\Master\Penyesuaianrencanaproject\Controllers\Controller;
use Illuminate\Support\Facades\Route;

// Resource utama
Route::resource('penyesuaianrencanaproject', Controller::class, [
    'names' => 'penyesuaianrencanaproject',
]);

// Custom route tambahan
Route::get('penyesuaianrencanaproject/data', [Controller::class, 'data'])
    ->name('penyesuaianrencanaproject.data');

Route::get('penyesuaianrencanaproject/import', [Controller::class, 'import'])
    ->name('penyesuaianrencanaproject.import');

Route::post('penyesuaianrencanaproject/import', [Controller::class, 'processImport'])
    ->name('penyesuaianrencanaproject.process-import');

Route::get('penyesuaianrencanaproject/export/{type?}', [Controller::class, 'export'])
    ->name('penyesuaianrencanaproject.export');

// Restore route
Route::patch('penyesuaianrencanaproject/{id}/restore', [Controller::class, 'restore'])
    ->name('penyesuaianrencanaproject.restore');

// Bulk operations
Route::delete('penyesuaianrencanaproject/bulk', [Controller::class, 'destroys'])
    ->name('penyesuaianrencanaproject.destroys');

// Dropdown generators
Route::get('penyesuaianrencanaproject/generatedropdownaktivitas', [Controller::class, 'generatedropdownaktivitas'])
    ->name('penyesuaianrencanaproject.generatedropdownaktivitas');

Route::get('penyesuaianrencanaproject/generatedropdownparent', [Controller::class, 'generatedropdownparent'])
    ->name('penyesuaianrencanaproject.generatedropdownparent');
