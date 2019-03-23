<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StorageLocation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relationships to always eager load
     *
     * @var array
     */
    protected $with = ['type'];

    /**
     * A location belongs to one storage type
     *
     * @return belongsTo
     */
    public function type()
    {
        return $this->belongsTo(StorageType::class, 'storage_type_id');
    }

    /**
     * A storage location belongs to many part categories
     *
     * @return hasMany
     */
    public function partCategories()
    {
        return $this->hasMany(PartCategory::class);
    }

    /**
     * A storage location has many parts
     *
     * @return hasManyThrough
     */
    public function parts()
    {
        return $this->hasManyThrough(Part::class, PartCategory::class);
    }
}
