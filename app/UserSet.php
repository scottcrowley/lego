<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSet extends Model
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
    protected $with = ['set'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['name'];

    /**
     * A UserSet belongs to a set
     *
     * @return belongsTo
     */
    public function set()
    {
        return $this->belongsTo(Set::class, 'set_num', 'set_num');
    }

    /**
     * Attribute getter for set->name
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->set->name;
    }
}
