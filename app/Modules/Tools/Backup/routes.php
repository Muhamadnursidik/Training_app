<?php
Route::resource('tools/backup', '\App\Modules\Tools\Backup\Controller', [
    'names' => 'tools.backup'
]);

Route::get('tools/backup/run',
'\App\Modules\Tools\Backup\Controller@run')->name('tools.backup.run');

Route::get('tools/backup/download',
'\App\Modules\Tools\Backup\Controller@download')->name('tools.backup.download');

Route::get('tools/backup/backupclean',
'\App\Modules\Tools\Backup\Controller@backupclean')->name('tools.backup.backupclean');