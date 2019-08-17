<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
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
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $with = ['category', 'storageLocation'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['category_label'];

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
}
