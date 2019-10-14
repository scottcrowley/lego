<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    /**
     * no timestamps needed
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['theme', 'set'];

    /**
     * An inventory has many inventory parts
     *
     * @return hasMany
     */
    public function parts()
    {
        return $this->hasMany(InventoryPart::class);
    }

    /**
     * An inventory belongs to a set.
     *
     * @return belongsTo
     */
    public function set()
    {
        // only use if ApiLegoController@getInventories is loading set & theme after pagination
        // return $this->belongsTo(Set::class, 'set_num', 'set_num')->with('setImageUrl');
        return $this->belongsTo(Set::class, 'set_num', 'set_num');
    }

    /**
     * An inventory has one theme through a set
     *
     * @return hasOneThrough
     */
    public function theme()
    {
        return $this->hasOneThrough(Theme::class, Set::class, 'set_num', 'id', 'set_num', 'theme_id');
    }

    /**
     * An inventory has many stickered parts
     *
     * @return hasMany
     */
    public function stickeredParts()
    {
        return $this->hasMany(StickeredPart::class);
    }

    /**
     * Getter for set->name
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->set->name;
    }

    /**
     * Getter for set->year
     *
     * @return string
     */
    public function getYearAttribute()
    {
        return $this->set->year;
    }

    /**
     * Getter for set->image_url
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->set->image_url;
    }

    /**
     * Getter for set->num_parts
     *
     * @return string
     */
    public function getNumPartsAttribute()
    {
        return $this->set->num_parts;
    }

    /**
     * Getter for set->theme_id
     *
     * @return string
     */
    public function getThemeIdAttribute()
    {
        return $this->theme->id;
    }

    /**
     * Getter for set->theme_label
     *
     * @return string
     */
    public function getThemeLabelAttribute()
    {
        return $this->theme->theme_label;
    }
}
