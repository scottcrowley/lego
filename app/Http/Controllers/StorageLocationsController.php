<?php

namespace App\Http\Controllers;

use App\StorageLocation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StorageLocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = StorageLocation::orderBy('name')->get();

        return view('storage.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $location = new StorageLocation;
        $types = \App\StorageType::orderBy('name')->get();
        return view('storage.locations.create', compact('location', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->validate([
            'name' => 'required|unique:storage_locations',
            'description' => 'required',
            'storage_type_id' => 'exists:storage_types,id'
        ]);

        $location = StorageLocation::create($data);

        session()->flash('flash', ['message' => 'Storage Location added successfully!', 'level' => 'success']);

        return redirect(route('storage.locations.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  StorageLocation  $location
     * @return \Illuminate\Http\Response
     */
    public function show(StorageLocation $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  StorageLocation  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(StorageLocation $location)
    {
        $types = \App\StorageType::orderBy('name')->get();
        return view('storage.locations.edit', compact('location', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StorageLocation  $location
     * @return \Illuminate\Http\Response
     */
    public function update(StorageLocation $location)
    {
        $data = request()->validate([
            'name' => ['required', 'unique:storage_locations', Rule::unique('storage_locations')->ignore($location->id)],
            'description' => 'required',
            'storage_type_id' => 'exists:storage_types,id'
        ]);

        $location->update($data);

        session()->flash('flash', ['message' => 'Storage Location updated successfully!', 'level' => 'success']);

        return redirect(route('storage.locations.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  StorageLocation  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(StorageLocation $location)
    {
        //
    }
}
