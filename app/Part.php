<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
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
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $with = ['category'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['category_label', 'image_url'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['partImageUrl', 'category', 'storageLocation'];

    /**
     * Add a new url to the partImageUrl relation
     *
     * @param string $url
     * @return void
     */
    public function addImageUrl($url)
    {
        $this->partImageUrl()->create(['image_url' => $url]);
    }

    /**
     * A part belongs to one part category
     *
     * @return belongsTo
     */
    public function category()
    {
        return $this->belongsTo(PartCategory::class, 'part_category_id');
    }

    /**
     * A part belongs to a storage location
     *
     * @return belongsToMany
     */
    public function storageLocation()
    {
        return $this->belongsToMany(StorageLocation::class, 'part_storage_location', 'part_num', 'storage_location_id', 'part_num', 'id');
        // return $this->hasOneThrough(StorageLocation::class, PartStorageLocation::class, 'part_num', 'id', 'part_num', 'storage_location_id');
    }

    /**
     * A part has many part image urls
     *
     * @return hasMany
     */
    public function partImageUrl()
    {
        return $this->hasMany(PartImageUrl::class, 'part_num', 'part_num');
    }

    /**
     * Custom getter for category name.
     *
     * @return string
     */
    public function getCategoryLabelAttribute()
    {
        return $this->category->name;
    }

    /**
     * Custom getter for storage location.
     *
     * @return string
     */
    public function getLocationAttribute()
    {
        return $this->storageLocation->first();
    }

    /**
     * Getter for image url
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        $partImageUrl = $this->partImageUrl;

        if (is_null($partImageUrl) || ! $partImageUrl->count()) {
            return '';
        }

        return $partImageUrl->first()->image_url;
    }
}
