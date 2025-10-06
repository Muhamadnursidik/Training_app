<?php

use App\Modules\Progress\Mingguan\Controllers\Controller;
use Illuminate\Support\Facades\Route;

// Resource utama
Route::resource('mingguan', Controller::class, [
    'names' => 'mingguan',
]);

// Custom route tambahan
Route::get('mingguan/data', [Controller::class, 'data'])
    ->name('mingguan.data');

Route::get('mingguan/import', [Controller::class, 'import'])
    ->name('mingguan.import');

Route::post('mingguan/import', [Controller::class, 'processImport'])
    ->name('mingguan.process-import');

Route::get('mingguan/export/{type?}', [Controller::class, 'export'])
    ->name('mingguan.export');

// Restore route
Route::patch('mingguan/{id}/restore', [Controller::class, 'restore'])
    ->name('mingguan.restore');

// Bulk operations
Route::delete('mingguan/bulk', [Controller::class, 'destroys'])
    ->name('mingguan.destroys');
