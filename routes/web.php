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
    'prefix' => 'profiles',
    'middleware' => 'auth'
], function () {
    Route::get('/', 'ProfilesController@show')->name('profiles');
    Route::get('/edit', 'ProfilesController@edit')->name('profiles.edit');
    Route::patch('/edit', 'ProfilesController@update')->name('profiles.update');
});

Route::group([
    'prefix' => 'credentials',
    'middleware' => 'auth'
], function () {
    Route::post('/credentials', 'CredentialsController@store')->name('credentials.store');
    Route::patch('/credentials', 'CredentialsController@update')->name('credentials.update');
    Route::get('/credentials/create', 'CredentialsController@create')->name('credentials.create');
    Route::get('/credentials/edit', 'CredentialsController@edit')->name('credentials.edit');
});

Route::group([
    'middleware' => 'rebrickable'
], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
});
