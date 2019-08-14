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
    Route::group([
        'prefix' => 'lego',
    ], function () {
        Route::view('/colors', 'lego.colors')->name('lego.colors');
        Route::view('/part_categories', 'lego.part_categories')->name('lego.part_categories');
        Route::view('/parts', 'lego.parts')->name('lego.parts');
        Route::view('/sets', 'lego.sets.index')->name('lego.sets.index');
        Route::view('/sets-grid', 'lego.sets.grid')->name('lego.sets.grid');
        Route::view('/themes', 'lego.themes')->name('lego.themes');
    });
    Route::group([
        'prefix' => 'legouser',
    ], function () {
        Route::view('/setlists', 'legouser.setlists')->name('legouser.setlists');
        Route::view('/sets', 'legouser.sets')->name('legouser.sets');
        Route::view('/parts', 'legouser.parts')->name('legouser.parts');
        Route::view('/loose_parts', 'legouser.loose_parts')->name('legouser.loose_parts');
        Route::view('/lost_parts', 'legouser.lost_parts')->name('legouser.lost_parts');
    });
});

Route::group([
    'prefix' => 'api/lego',
    'middleware' => 'rebrickable'
], function () {
    Route::get('/colors', 'ApiLegoController@getColors')->name('api.lego.colors');
    Route::get('/part_categories', 'ApiLegoController@getPartCategories')->name('api.lego.part_categories');
    Route::get('/part_relationships', 'ApiLegoController@getPartRelationships')->name('api.lego.part_relationships');
    Route::get('/parts', 'ApiLegoController@getParts')->name('api.lego.parts');
    // Route::get('/parts/{partNum}', 'ApiLegoController@getPart')->name('api.lego.parts.show');
    Route::get('/sets', 'ApiLegoController@getSets')->name('api.lego.sets');
    // Route::get('/sets/{setNum}', 'ApiLegoController@getSet')->name('api.lego.sets.show');
    Route::get('/themes', 'ApiLegoController@getThemes')->name('api.lego.themes');
    // Route::get('/themes/{id}', 'ApiLegoController@getTheme')->name('api.lego.themes.show');
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
