<?php

namespace App\Http\Controllers;

use App\PartRelationship;
use Illuminate\Http\Request;

class PartRelationshipsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partRelationships = PartRelationship::all();

        return view('part_relationships.index', compact('partRelationships'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'child_part_num' => 'required|exists:parts,part_num',
            'parent_part_num' => 'required|exists:parts,part_num',
        ]);

        $partRelationship = PartRelationship::create($data);

        session()->flash('flash', ['message' => 'Part Relationship added successfully!', 'level' => 'success']);

        if ($request->wantsJson()) {
            return response($partRelationship, 201);
        }

        return redirect(route('part_relationships.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PartRelationship  $partRelationship
     * @return \Illuminate\Http\Response
     */
    public function show(PartRelationship $partRelationship)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PartRelationship  $partRelationship
     * @return \Illuminate\Http\Response
     */
    public function edit(PartRelationship $partRelationship)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PartRelationship  $partRelationship
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PartRelationship $partRelationship)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PartRelationship  $partRelationship
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartRelationship $partRelationship)
    {
        //
    }
}
