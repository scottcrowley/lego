<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryPart extends Model
{
    /**
     * no timestamps needed
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['name', 'category_label', 'image_url', 'color_name', 'ldraw_image_url'];

    protected $ldrawBaseUrl = 'https://cdn.rebrickable.com/media/thumbs/parts/ldraw/{color_id}/{part_num}.png/250x250p.png';

    /**
     * An inventory part_num has one part
     *
     * @return hasOne
     */
    public function part()
    {
        return $this->hasOne(Part::class, 'part_num', 'part_num');
    }

    /**
     * An inventory color_id has one color
     *
     * @return hasOne
     */
    public function color()
    {
        return $this->hasOne(Color::class, 'id', 'color_id');
    }

    /**
     * An inventory part belongs to one inventory
     *
     * @return belongsTo
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'id');
    }

    /**
     * Simple getter for the LDraw base url
     *
     * @return string
     */
    public function getLdrawBaseUrl()
    {
        return $this->ldrawBaseUrl;
    }

    /**
     * Getter for part->name
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->part->name;
    }

    /**
     * Getter for part->category_label
     *
     * @return string
     */
    public function getCategoryLabelAttribute()
    {
        return $this->part->categoryLabel;
    }

    /**
     * Getter for part->image_url
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->part->imageUrl;
    }

    /**
     * Getter for color->name
     *
     * @return string
     */
    public function getColorNameAttribute()
    {
        return $this->color->name;
    }

    /**
     * Getter for part->location
     *
     * @return string
     */
    public function getLocationNameAttribute()
    {
        $location = $this->part->storageLocation;

        return ($location->count()) ? $location->first()->location_name : 'None';
    }

    public function getLdrawImageUrlAttribute()
    {
        return str_replace(['{color_id}', '{part_num}'], [$this->color_id, $this->part_num], $this->ldrawBaseUrl);
    }
}
