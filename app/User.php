<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * Checks if a user has valid rebrickable credentials set in the env file
     *
     * @return bool
     */
    public function validCredentials()
    {
        $credentials = config('rebrickable.api');

        return (
            ! is_null($credentials) &&
            $credentials['email'] != '' &&
            $credentials['password'] != '' &&
            $credentials['key'] != ''
        );
    }
}
