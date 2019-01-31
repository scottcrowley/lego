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

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
