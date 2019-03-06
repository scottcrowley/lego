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
    'prefix' => 'api/lego',
    'middleware' => 'rebrickable'
], function () {
    Route::get('/clear/{type}', 'RebrickableApiLegoController@clearCache')->name('api.lego.clear');
    Route::get('/colors', 'RebrickableApiLegoController@getColors')->name('api.lego.colors');
    Route::get('/themes', 'RebrickableApiLegoController@getThemes')->name('api.lego.themes');
    Route::get('/themes/{id}', 'RebrickableApiLegoController@getTheme')->name('api.lego.themes.show');
    Route::get('/part_categories', 'RebrickableApiLegoController@getPartCategories')->name('api.lego.part_categories');
    Route::get('/sets', 'RebrickableApiLegoController@getSets')->name('api.lego.sets');
    Route::get('/sets/{setNum}', 'RebrickableApiLegoController@getSet')->name('api.lego.sets.show');
    Route::get('/parts', 'RebrickableApiLegoController@getParts')->name('api.lego.parts');
    Route::get('/parts/{partNum}', 'RebrickableApiLegoController@getPart')->name('api.lego.parts.show');
});

Route::group([
    'prefix' => 'api/users',
    'middleware' => 'rebrickable'
], function () {
    Route::get('/clear/{type}', 'RebrickableApiUserController@clearCache')->name('api.users.clear');
    Route::get('/token', 'RebrickableApiUserController@getToken')->name('api.user.token');
    Route::get('/setlists', 'RebrickableApiUserController@getSetLists')->name('api.users.setlists');
    Route::get('/sets', 'RebrickableApiUserController@getSets')->name('api.users.sets');
    Route::get('/allparts', 'RebrickableApiUserController@getAllParts')->name('api.users.allparts');
    Route::get('/profile', 'RebrickableApiUserController@getProfile')->name('api.users.profile');
});

Route::group([
    'prefix' => 'csv',
    'middleware' => 'rebrickable'
], function () {
    Route::get('/clear/{type}', 'RebrickableCsvController@clearCache');
    Route::get('/{type}', 'RebrickableCsvController@getType');
});
