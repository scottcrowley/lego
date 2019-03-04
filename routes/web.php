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
    'prefix' => 'storage/types',
    'middleware' => 'auth'
], function () {
    Route::get('', 'StorageTypesController@index')->name('storage.types.index');
    Route::get('/create', 'StorageTypesController@create')->name('storage.types.create');
    Route::post('', 'StorageTypesController@store')->name('storage.types.store');
    Route::get('/{type}', 'StorageTypesController@show')->name('storage.types.show');
    Route::get('/{type}/edit', 'StorageTypesController@edit')->name('storage.types.edit');
    Route::patch('/{type}', 'StorageTypesController@update')->name('storage.types.update');
    Route::delete('/{type}', 'StorageTypesController@destroy')->name('storage.types.delete');
});

Route::group([
    'prefix' => 'storage/locations',
    'middleware' => 'auth'
], function () {
    Route::get('', 'StorageLocationsController@index')->name('storage.locations.index');
    Route::get('/create', 'StorageLocationsController@create')->name('storage.locations.create');
    Route::post('', 'StorageLocationsController@store')->name('storage.locations.store');
    Route::get('/{location}/edit', 'StorageLocationsController@edit')->name('storage.locations.edit');
    Route::patch('/{location}', 'StorageLocationsController@update')->name('storage.locations.update');
});

Route::group([
    'middleware' => 'rebrickable'
], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
});

Route::group([
    'prefix' => 'api',
    'middleware' => 'rebrickable'
], function () {
    Route::get('/clear/{type}', 'RebrickableApiController@clearCache')->name('api.clear');
    Route::get('/colors', 'RebrickableApiController@getColors')->name('api.colors');
    Route::get('/themes', 'RebrickableApiController@getThemes')->name('api.themes');
    Route::get('/themes/{id}', 'RebrickableApiController@getTheme')->name('api.themes.show');
    Route::get('/part_categories', 'RebrickableApiController@getPartCategories')->name('api.part_categories');
    Route::get('/sets', 'RebrickableApiController@getSets')->name('api.sets');
    Route::get('/sets/{setNum}', 'RebrickableApiController@getSet')->name('api.sets.show');
});
