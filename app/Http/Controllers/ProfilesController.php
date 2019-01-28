<?php

namespace App\Http\Controllers;

use App\User;

class ProfilesController extends Controller
{
    public function index(User $user = null)
    {
        $user = $user ?: auth()->user();

        return view('profiles', compact('user'));
    }
}
