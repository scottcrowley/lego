<?php

namespace App\Http\Controllers;

use App\Theme;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ThemesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $themes = Theme::all();

        return view('themes.index', compact('themes'));
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
            'name' => 'required|unique:themes',
            'parent_id' => 'nullable|exists:themes,id'
        ]);

        $theme = Theme::create($data);

        session()->flash('flash', ['message' => 'Theme added successfully!', 'level' => 'success']);

        if ($request->wantsJson()) {
            return response($theme, 201);
        }

        return redirect(route('themes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function show(Theme $theme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function edit(Theme $theme)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Theme $theme)
    {
        $data = $request->validate([
            'name' => ['required', Rule::unique('themes')->ignore($theme->id)],
            'parent_id' => 'nullable|exists:themes,id'
        ]);

        $theme->update($data);
        
        session()->flash('flash', ['message' => 'Theme updated successfully!', 'level' => 'success']);

        if ($request->wantsJson()) {
            return response($theme);
        }

        return redirect(route('themes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function destroy(Theme $theme)
    {
        //
    }
}
