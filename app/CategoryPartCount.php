<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryPartCount extends Model
{
    /**
     * no timestamps needed
     *
     * @var boolean
     */
    public $timestamps = false;

    protected $table = 'category_part_count';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['part_category_id', 'part_count'];
}
