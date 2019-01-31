<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CredentialsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('credentials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if (! is_null(auth()->user()->credentials)) {
            session()->flash('flash', ['message' => 'Your Rebrickable credentials already exist.', 'level' => 'danger']);
            return redirect()->back()->withInput();
        }

        $data = $this->validateData();

        auth()->user()->credentials()->create($data);

        session()->flash('flash', ['message' => 'Rebrickable credentials added successfully!', 'level' => 'success']);

        return redirect(route('profiles.edit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = \App\User::find(auth()->id());

        return view('credentials.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $this->validateData();

        auth()->user()->credentials()->update($data);

        session()->flash('flash', ['message' => 'Rebrickable credentials were updated successfully!', 'level' => 'success']);

        return redirect(route('profiles.edit'));
    }

    protected function validateData()
    {
        return request()->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
            'api_key' => 'required|max:255'
        ]);
    }
}
