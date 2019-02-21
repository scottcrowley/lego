<?php

namespace App\Http\Controllers;

use App\StorageType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StorageTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = StorageType::orderBy('name')->get();

        return view('storage.types.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $type = new StorageType;
        return view('storage.types.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->validate([
            'name' => 'required|unique:storage_types',
            'description' => 'required'
        ]);

        $type = StorageType::create($data);

        session()->flash('flash', ['message' => 'Storage Type added successfully!', 'level' => 'success']);

        return redirect(route('storage.types.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  StorageType  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(StorageType $type)
    {
        return view('storage.types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StorageType  $type
     * @return \Illuminate\Http\Response
     */
    public function update(StorageType $type)
    {
        $data = request()->validate([
            'name' => ['required', Rule::unique('storage_types')->ignore($type->id)],
            'description' => 'required'
        ]);

        $type->update($data);

        session()->flash('flash', ['message' => 'Storage Type updated successfully!', 'level' => 'success']);

        return redirect(route('storage.types.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  StorageType  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(StorageType $type)
    {
        $type->delete();

        session()->flash('flash', 'The storage type was deleted successfully!');

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect(route('storage.types.index'));
    }
}
