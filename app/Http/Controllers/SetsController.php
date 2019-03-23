<?php

namespace App\Http\Controllers;

use App\Set;
use Illuminate\Http\Request;

class SetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sets = Set::orderBy('name')->get();

        return view('sets.index', compact('sets'));
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
            'set_num' => 'required|unique:sets',
            'name' => 'required|unique:sets',
            'year' => 'required',
            'set_img_url' => 'url|nullable',
            'set_url' => 'url|nullable',
            'theme_id' => 'nullable|exists:themes,id',
            'num_parts' => 'required'
        ]);

        $set = Set::create($data);

        session()->flash('flash', ['message' => 'Set added successfully!', 'level' => 'success']);

        if (request()->wantsJson()) {
            return response($set, 201);
        }

        return redirect(route('sets.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Set  $set
     * @return \Illuminate\Http\Response
     */
    public function show(Set $set)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Set  $set
     * @return \Illuminate\Http\Response
     */
    public function edit(Set $set)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Set  $set
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Set $set)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Set  $set
     * @return \Illuminate\Http\Response
     */
    public function destroy(Set $set)
    {
        //
    }
}
