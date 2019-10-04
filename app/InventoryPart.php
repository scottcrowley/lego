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
    protected $appends = ['name', 'part_category_id', 'category_label', 'image_url', 'color_name', 'ldraw_image_url'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['userParts'];

    /**
     * Base url for a parts ldraw image
     *
     * @var string
     */
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
     * An inventory part has one color
     *
     * @return hasOne
     */
    public function color()
    {
        return $this->hasOne(Color::class, 'id', 'color_id');
    }

    /**
     * An inventory part can have one user part with the same color
     *
     * @return hasOne
     */
    public function userParts()
    {
        return $this->hasMany(UserPart::class, 'part_num', 'part_num')->without('part')->without('color');
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
     * Getter for part->part_category_id
     *
     * @return int
     */
    public function getPartCategoryIdAttribute()
    {
        return $this->part->part_category_id;
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

        return ($location) ? $location->location_name : 'None';
    }

    /**
     * Getter for the parts ldraw image url
     *
     * @return string
     */
    public function getLdrawImageUrlAttribute()
    {
        return str_replace(['{color_id}', '{part_num}'], [$this->color_id, $this->part_num], $this->ldrawBaseUrl);
    }

    /**
     * Getter for the the number of owned parts with the same color
     *
     * @return int
     */
    public function getTotalOwned()
    {
        $part = $this->userParts->where('color_id', $this->color_id)->first();

        if (is_null($part)) {
            return 0;
        }
        return $part->quantity;
    }

    /**
     * Getter for the the number of owned parts with the same color label
     *
     * @return int
     */
    public function getQuantityLabelAttribute()
    {
        $label = $this->quantity.' / '.$this->getTotalOwned();

        return $label;
    }
}
