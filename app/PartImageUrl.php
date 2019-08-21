<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartImageUrl extends Model
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
    protected $fillable = ['part_num', 'image_url'];
}
