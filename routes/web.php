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

use App\Inventory;
use App\StorageLocation;

if (App::environment() === 'testing') {
    Route::get('__testing__/create/{model}', function ($model) {
        return factory("App\\{$model}")->create(request()->all());
    });

    Route::get('__testing__/login', function () {
        $user = factory('App\User')->create(request()->all());

        auth()->login($user);
        return $user;
    });
}

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
    Route::get('/{type}/copy', 'StorageTypesController@create')->name('storage.types.copy');
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
    Route::get('/{location}/copy', 'StorageLocationsController@create')->name('storage.locations.copy');
    Route::post('', 'StorageLocationsController@store')->name('storage.locations.store');
    Route::get('/{location}/edit', 'StorageLocationsController@edit')->name('storage.locations.edit');
    Route::patch('/{location}', 'StorageLocationsController@update')->name('storage.locations.update');
    Route::get('/{location}/parts', function (StorageLocation $location) {
        $allLocations = StorageLocation::all()->sortBy('location_name')->values();
        return view('storage.locations.parts.index', compact('location', 'allLocations'));
    })->name('storage.locations.parts.index');
    Route::get('/{location}/parts/edit', function (StorageLocation $location) {
        $allLocations = StorageLocation::all()->sortBy('location_name')->values();
        return view('storage.locations.parts.edit', compact('location', 'allLocations'));
    })->name('storage.locations.parts.edit');
    Route::get('/{location}/parts/individual', function (StorageLocation $location) {
        $allLocations = StorageLocation::all()->sortBy('location_name')->values();
        return view('storage.locations.parts.individual', compact('location', 'allLocations'));
    })->name('storage.locations.parts.individual');
    Route::get('/{location}/parts/move', function (StorageLocation $location) {
        $allLocations = StorageLocation::where('id', '<>', $location->id)->get()->sortBy('location_name')->values();
        return view('storage.locations.parts.move', compact('location', 'allLocations'));
    })->name('storage.locations.parts.move');
    Route::get('/parts/unassigned', function () {
        $allLocations = StorageLocation::all()->sortBy('location_name')->values();
        return view('storage.locations.parts.unassigned', compact('allLocations'));
    })->name('storage.locations.parts.unassigned');
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
        Route::view('/parts', 'lego.parts.index')->name('lego.parts.index');
        Route::view('/parts-grid', 'lego.parts.grid')->name('lego.parts.grid');
        Route::view('/inventories', 'lego.inventories.index')->name('lego.inventories.index');
        Route::view('/inventories-grid', 'lego.inventories.grid')->name('lego.inventories.grid');
        Route::get('/inventory-parts-grid/{inventory}', function (Inventory $inventory) {
            return view('lego.inventory_parts.grid', compact('inventory'));
        })->name('lego.inventory_parts.grid');
        Route::get('/inventory-parts-grid/{inventory}/stickered', function (Inventory $inventory) {
            return view('lego.inventory_parts.stickered', compact('inventory'));
        })->name('lego.inventory_parts.stickered');
        Route::get('/inventory-parts/{inventory}', function (Inventory $inventory) {
            return view('lego.inventory_parts.index', compact('inventory'));
        })->name('lego.inventory_parts.index');
        Route::view('/sets', 'lego.sets.index')->name('lego.sets.index');
        Route::view('/sets-grid', 'lego.sets.grid')->name('lego.sets.grid');
        Route::view('/themes', 'lego.themes')->name('lego.themes');
    });
    Route::group([
        'prefix' => 'legouser',
    ], function () {
        Route::view('/parts/all', 'legouser.parts.all')->name('legouser.parts.all');
        Route::view('/parts/individual', 'legouser.parts.individual')->name('legouser.parts.individual');
        Route::view('/sets', 'legouser.sets.index')->name('legouser.sets.index');
        Route::view('/sets-grid', 'legouser.sets.grid')->name('legouser.sets.grid');
        Route::view('/loose-parts', 'legouser.loose_parts')->name('legouser.loose_parts');
        Route::view('/lost-parts', 'legouser.lost_parts')->name('legouser.lost_parts');
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
    Route::get('/sets', 'ApiLegoController@getSets')->name('api.lego.sets');
    Route::get('/themes', 'ApiLegoController@getThemes')->name('api.lego.themes');
    Route::get('/inventories', 'ApiLegoController@getInventories')->name('api.lego.inventories');
    Route::get('/inventory_parts/{inventory}', 'ApiLegoController@getInventoryParts')->name('api.lego.inventory_parts');
    Route::get('/inventory_parts/selection/{inventory}', 'ApiLegoController@getPartSelection')->name('api.lego.inventory_parts.selection.get');
    Route::post('/inventory_parts/selection', 'ApiLegoController@updatePartSelection')->name('api.lego.inventory_parts.selection.update');
    Route::post('/inventory_parts/{inventory}/stickered', 'ApiLegoController@addStickeredPart')->name('api.lego.inventory_parts.stickered.add');
    Route::delete('/inventory_parts/{inventory}/stickered/{part}/{color}', 'ApiLegoController@removeStickeredPart')->name('api.lego.inventory_parts.stickered.remove');
});

Route::group([
    'prefix' => 'api/users',
    'middleware' => 'rebrickable'
], function () {
    Route::get('/token', 'ApiUserController@getToken')->name('api.users.token');
    Route::get('/profile', 'ApiUserController@getProfile')->name('api.users.profile');
    Route::get('/sets', 'ApiUserController@getSets')->name('api.users.sets');
    Route::get('/parts/all', 'ApiUserController@getAllParts')->name('api.users.parts.all');
    Route::get('/parts/individual', 'ApiUserController@getAllIndividualParts')->name('api.users.parts.individual');
    Route::get('/storage/locations/parts/unassigned', 'ApiUserController@getAllUnassignedParts')->name('api.users.storage.locations.unassigned.parts');
    Route::post('/storage/locations/parts/unassigned/{location}', 'ApiUserController@moveUnassignedParts')->name('api.users.storage.locations.unassigned.parts.move');
    Route::get('/storage/locations/{location}/parts', 'ApiUserController@getStorageLocationParts')->name('api.users.storage.locations.parts');
    Route::post('/storage/locations/{location}/parts/{newLocation}', 'ApiUserController@moveStorageLocationParts')->name('api.users.storage.locations.parts.move');
    Route::get('/storage/locations/{location}/parts/individual', 'ApiUserController@getStorageLocationIndividualParts')->name('api.users.storage.locations.parts.individual');
    Route::get('/storage/locations/{location}/parts/edit', 'ApiUserController@editStorageLocationParts')->name('api.users.storage.locations.parts.edit');
    Route::get('/storage/locations/{location}/parts/toggle/{part}', 'ApiUserController@togglePartInLocation')->name('api.users.storage.locations.parts.toggle');
});

Route::group([
    'prefix' => 'api/rebrickable',
    'middleware' => 'rebrickable'
], function () {
    Route::get('/part_categories/{category}/parts', 'RebrickableApiController@getPartsByCategory')->name('api.rebrickable.parts_by_category');
});
