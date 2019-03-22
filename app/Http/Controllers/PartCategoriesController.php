<?php

namespace App\Http\Controllers;

use App\PartCategory;
use Illuminate\Http\Request;

class PartCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = PartCategory::all();

        return view('part_categories.index', compact('categories'));
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
            'name' => 'required|unique:part_categories'
        ]);

        $category = PartCategory::create($data);

        session()->flash('flash', ['message' => 'Part Category added successfully!', 'level' => 'success']);

        if (request()->wantsJson()) {
            return response($category, 201);
        }

        return redirect(route('part_categories.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PartCategory  $partCategory
     * @return \Illuminate\Http\Response
     */
    public function show(PartCategory $partCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PartCategory  $partCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(PartCategory $partCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PartCategory  $partCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PartCategory $partCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PartCategory  $partCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartCategory $partCategory)
    {
        //
    }
}
