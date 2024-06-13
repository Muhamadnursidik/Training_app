<?php
Route::resource('notification', '\App\Modules\Mapping\Notifications\Controller', [
    'names' => 'notification'
]);

Route::get('notification/read/{id}',
'\App\Modules\Mapping\Notifications\Controller@read')->name('notification.read');

Route::get('notification/count',
'\App\Modules\Mapping\Notifications\Controller@count')->name('notification.count');

Route::get('notification/sendmail',
'\App\Modules\Mapping\Notifications\Controller@sendmail')->name('notification.sendmail');

Route::get('notification/list',
'\App\Modules\Mapping\Notifications\Controller@list')->name('notification.list');

Route::get('notification/list-data',
'\App\Modules\Mapping\Notifications\Controller@listData')->name('notification.listData');