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
        return $this->belongsTo(Set::class, 'set_num', 'set_num');
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
        return $this->set->theme_id;
    }

    /**
     * Getter for set->theme_label
     *
     * @return string
     */
    public function getThemeLabelAttribute()
    {
        return $this->set->theme->theme_label;
    }
}
