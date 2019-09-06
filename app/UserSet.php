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
    protected $with = ['set', 'inventory'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['set', 'inventory'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['name', 'theme_label', 'year', 'num_parts', 'image_url', 'inventory_id'];

    /**
     * A UserSet belongs to a set
     *
     * @return belongsTo
     */
    public function set()
    {
        return $this->belongsTo(Set::class, 'set_num', 'set_num')->with('theme');
    }

    /**
     * A UserSet has one inventory
     *
     * @return belongsTo
     */
    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'set_num', 'set_num')->withDefault('id', null);
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

    /**
     * Attribute getter for set->theme->theme_label
     *
     * @return string
     */
    public function getThemeLabelAttribute()
    {
        return $this->set->theme->theme_label;
    }

    /**
     * Attribute getter for set->year
     *
     * @return string
     */
    public function getYearAttribute()
    {
        return $this->set->year;
    }

    /**
     * Attribute getter for set->num_parts
     *
     * @return string
     */
    public function getNumPartsAttribute()
    {
        return $this->set->num_parts;
    }

    /**
     * Attribute getter for set->image_url
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->set->image_url;
    }

    /**
     * Attribute getter for inventory->id
     *
     * @return string
     */
    public function getInventoryIdAttribute()
    {
        return $this->inventory->id;
    }
}
