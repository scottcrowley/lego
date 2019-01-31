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
        'password', 'remember_token',
        'rebrickable_api_key'
    ];

    /**
     * The relationships to always eager-load.
     *
     * @var array
     */
    protected $with = ['credentials'];

    public function credentials()
    {
        return $this->hasOne(RebrickableCredentials::class, 'user_id');
    }

    public function validAPI()
    {
        $credentials = $this->credentials;
        return (! is_null($credentials) && $credentials->api_key != '');
    }
}
