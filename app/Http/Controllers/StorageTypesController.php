<?php

namespace App\Http\Controllers;

use App\StorageType;
use Illuminate\Http\Request;

class StorageTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $type = StorageType::create($data);

        session()->flash('flash', ['message' => 'Storage Type added successfully!', 'level' => 'success']);

        return redirect(route('storage.types'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            'name' => 'required',
            'description' => 'required'
        ]);

        $type->update($data);

        session()->flash('flash', ['message' => 'Storage Type updated successfully!', 'level' => 'success']);

        return redirect(route('storage.types'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
