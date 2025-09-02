<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => ['password_expired']], function () {
Route::group(['middleware' => ['auth'], 'prefix' => '/'], function () {
    Route::get('/', '\App\Http\Controllers\HomeController@index');
    Route::get('/home', '\App\Http\Controllers\HomeController@index')->name('home');
    Route::get('/dashboard', '\App\Http\Controllers\HomeController@index')->name('home');
    Route::get('/usermanual', '\App\Http\Controllers\HomeController@usermanual')->name('usermanual');
});

// Authentication Routes...
Route::get('login', '\App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::post('login', '\App\Http\Controllers\Auth\LoginController@login');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
    Route::get('/delete', '\App\Http\Controllers\CustomFMDeleteController@getDelete')->name('unisharp.lfm.getDelete'); //custom route
});

Route::name('admin.')->middleware(['auth', 'acl'])->group(function () {
    include app_path('Modules/Admin/routes.php');
});

Route::name('master.')->middleware(['auth', 'acl'])->group(function () {
    include app_path('Modules/Master/routes.php');
});

Route::name('mapping.')->middleware(['auth', 'acl'])->group(function () {
    include app_path('Modules/Mapping/routes.php');
});

Route::name('tools.')->middleware(['auth', 'acl'])->group(function () {
    include app_path('Modules/Tools/routes.php');
});

});

