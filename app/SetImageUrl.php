<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetImageUrl extends Model
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
    protected $fillable = ['set_num', 'image_url'];
}
