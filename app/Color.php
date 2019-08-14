<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    /**
     * no timestamps needed
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
