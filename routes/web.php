<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'prefix' => 'profile',
    'middleware' => 'auth'
], function () {
    Route::get('/', 'ProfileController@show')->name('profile');
    Route::get('/edit', 'ProfileController@edit')->name('profile.edit');
    Route::patch('/edit', 'ProfileController@update')->name('profile.update');
});

Route::group([
    'prefix' => 'storage',
    'middleware' => 'auth'
], function () {
    Route::get('/types', 'StorageTypesController@index')->name('storage.types.index');
    Route::get('/types/create', 'StorageTypesController@create')->name('storage.types.create');
    Route::post('/types', 'StorageTypesController@store')->name('storage.types.store');
    Route::patch('/types/{type}', 'StorageTypesController@update')->name('storage.types.update');
});

Route::group([
    'middleware' => 'rebrickable'
], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
});
