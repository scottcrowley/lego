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
    'middleware' => 'auth'
], function () {
    Route::get('/themes', 'ThemesController@index')->name('themes.index');
    Route::post('/themes', 'ThemesController@store')->name('themes.store');
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
        Route::get('/sets', function () {
            return view('lego.sets');
        })->name('lego.sets');
        Route::get('/themes', function () {
            return view('lego.themes');
        })->name('lego.themes');
        Route::get('/part_categories', function () {
            return view('lego.part_categories');
        })->name('lego.part_categories');
        Route::get('/parts', function () {
            return view('lego.parts');
        })->name('lego.parts');
        Route::get('/colors', function () {
            return view('lego.colors');
        })->name('lego.colors');
    });
    Route::group([
        'prefix' => 'legouser',
    ], function () {
        Route::get('/setlists', function () {
            return view('legouser.setlists');
        })->name('legouser.setlists');
        Route::get('/sets', function () {
            return view('legouser.sets');
        })->name('legouser.sets');
        Route::get('/parts', function () {
            return view('legouser.parts');
        })->name('legouser.parts');
        Route::get('/loose_parts', function () {
            return view('legouser.loose_parts');
        })->name('legouser.loose_parts');
        Route::get('/lost_parts', function () {
            return view('legouser.lost_parts');
        })->name('legouser.lost_parts');
    });
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
