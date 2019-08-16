<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartCategory extends Model
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
     * A part category belongs to many storage locations
     *
     * @return belongsToMany
     */
    public function storageLocation()
    {
        return $this->belongsToMany(StorageLocation::class);
    }

    /**
     * A part category has many parts
     *
     * @return hasMany
     */
    public function parts()
    {
        return $this->hasMany(Part::class);
    }

    /**
     * Custom getter for part count.
     *
     * @return int
     */
    public function getPartCountAttribute()
    {
        return $this->parts->count();
    }
}
