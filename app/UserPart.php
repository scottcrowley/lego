<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPart extends Model
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
    protected $with = ['part'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['name'];

    /**
     * A UserPart belongs to a Part
     *
     * @return belongsTo
     */
    public function part()
    {
        return $this->belongsTo(Part::class, 'part_num', 'part_num');
    }

    /**
     * Attribute getter for part->name
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->part->name;
    }
}
