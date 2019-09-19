<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPart extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'part_num';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
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
    protected $fillable = ['part_num', 'color_id', 'quantity'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $with = ['part', 'color'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['name', 'color_name', 'image_url', 'part_category_id', 'category_label', 'location', 'location_name'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['color', 'part'];

    /**
     * Base url for a parts ldraw image
     *
     * @var string
     */
    protected $ldrawBaseUrl = 'https://cdn.rebrickable.com/media/thumbs/parts/ldraw/{color_id}/{part_num}.png/250x250p.png';

    /**
     * A UserPart belongs to a Part
     *
     * @return belongsTo
     */
    public function part()
    {
        return $this->belongsTo(Part::class, 'part_num', 'part_num')->with('storageLocation')->with('partImageUrl');
    }

    /**
     * A UserPart has one Color
     *
     * @return hasOne
     */
    public function color()
    {
        return $this->hasOne(Color::class, 'id', 'color_id');
    }

    /**
     * A UserPart has one storage location
     *
     * @return hasOneThrough
     */
    public function storageLocation()
    {
        return $this->hasOneThrough(StorageLocation::class, PartStorageLocation::class, 'part_num', 'id', 'part_num', 'storage_location_id');
    }

    /**
     * Toggle storage location association for this part
     *
     * @param StorageLocation $location
     * @return void
     */
    public function toggleLocation(StorageLocation $location)
    {
        $location->togglePart($this);
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

    /**
     * Attribute getter for color->name
     *
     * @return string
     */
    public function getColorNameAttribute()
    {
        return $this->color->name;
    }

    /**
     * Attribute getter for part->image_url
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->part->image_url;
    }

    /**
     * Attribute getter for part->category_id
     *
     * @return int
     */
    public function getPartCategoryIdAttribute()
    {
        return $this->part->part_category_id;
    }

    /**
     * Attribute getter for part->category_label
     *
     * @return string
     */
    public function getCategoryLabelAttribute()
    {
        return $this->part->category_label;
    }

    /**
     * Attribute getter for part->location
     *
     * @return string
     */
    public function getLocationAttribute()
    {
        return $this->part->location;
    }

    /**
     * Attribute getter for part->location->location_name
     *
     * @return string
     */
    public function getLocationNameAttribute()
    {
        return (is_null($this->part->location)) ? 'None' : $this->part->location->location_name;
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
     * Public Getter for the ldrawBaseUrl property
     *
     * @return string
     */
    public function getLdrawBaseUrl()
    {
        return $this->ldrawBaseUrl;
    }
}
