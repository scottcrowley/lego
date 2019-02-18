<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RebrickableCredentials extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    // protected $rebrickableApi;

    // public function __construct(RebrickableApi $rebrickableApi, $attributes = [])
    // {
    //     parent::__construct($attributes);

    //     $this->rebrickableApi = $rebrickableApi;
    // }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::created(function ($RebrickableCredentials) {
    //         $RebrickableCredentials->generateUserToken();
    //     });
    // }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    protected function generateUserToken()
    {
        // return $rebrickableApi->generateToken();
    }
}
