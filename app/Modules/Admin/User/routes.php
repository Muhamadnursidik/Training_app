<?php
Route::resource('user', '\App\Modules\Admin\User\Controller', [
    'names' => 'user'
]);

Route::get('change-password',
'\App\Modules\Admin\User\Controller@formchangepassword')->name('changepassword');

Route::post('save-change-password',
'\App\Modules\Admin\User\Controller@changePassword')->name('savechangepassword');

Route::get('user/reset-password',
'\App\Modules\Admin\User\Controller@resetPassword')->name('user.resetpassword');

Route::get('user/release',
'\App\Modules\Admin\User\Controller@release')->name('user.release');

Route::get('user/import',
'\App\Modules\Admin\User\Controller@import')->name('user.import');

Route::post('user/import',
'\App\Modules\Admin\User\Controller@importuser')->name('user.importuser');