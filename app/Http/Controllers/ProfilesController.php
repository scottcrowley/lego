<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Validation\Rule;

class ProfilesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user = null)
    {
        $user = $user ?: auth()->user();

        return view('profiles.index', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();

        return view('profiles.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $user = auth()->user();
        $user->update(
            request()->validate([
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            ])
        );

        return redirect(route('profiles'));
    }
}
