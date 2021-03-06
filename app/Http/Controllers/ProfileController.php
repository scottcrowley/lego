<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Rules\CurrentPassword;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
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

        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = User::find(auth()->id());

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        if (! $request->filled('current_password')) {
            $input = $request->except(['current_password', 'password', 'password_confirmation']);
        } else {
            $input = $request->all();
        }

        $validator = Validator::make($input, [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => [
                new CurrentPassword,
            ],
            'password' => 'required_with:current_password|different:current_password|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->except(['current_password', 'password', 'password_confirmation']));
        }

        $data = Arr::except($validator->attributes(), ['current_password', 'password_confirmation']);
        if ($request->filled('password')) {
            $data['password'] = \Hash::make($data['password']);
        }

        $user->update($data);

        session()->flash('flash', ['message' => 'The profile was successfully updated!', 'level' => 'success']);

        return redirect(route('profile'));
    }
}
