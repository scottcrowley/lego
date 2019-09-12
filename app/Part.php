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
        return $this->hasOneThrough(StorageLocation::class, PartStorageLocation::class, 'part_num', 'id', 'part_num', 'storage_location_id');
    }

    /**
     * A part has one part image url
     *
     * @return hasMany
     */
    public function partImageUrl()
    {
        return $this->hasOne(PartImageUrl::class, 'part_num', 'part_num');
    }

    /**
     * A part has many user part associations
     *
     * @return hasMany
     */
    public function userParts()
    {
        return $this->hasMany(UserPart::class, 'part_num', 'part_num');
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
        return $this->storageLocation;
    }

    /**
     * Getter for image url
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return optional($this->partImageUrl)->image_url;
    }

    /**
     * Getter for if the user owns this part
     *
     * @return bool
     */
    public function getOwnsPartAttribute()
    {
        return ! $this->userParts->isEmpty();
    }

    /**
     * Getter for quantity of owned user parts
     *
     * @return int
     */
    public function getOwnedPartCountAttribute()
    {
        $userParts = $this->userParts;

        if ($userParts->isEmpty()) {
            return 0;
        }

        return $userParts->sum('quantity');
    }

    /**
     * Getter for owned part location if available
     *
     * @return string
     */
    public function getOwnedPartLocationNameAttribute()
    {
        $userParts = $this->userParts;

        if ($userParts->isEmpty()) {
            return '';
        }

        return $userParts->first()->location_name;
    }
}
