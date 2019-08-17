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
     * Add a part to the storage location
     *
     * @param Part $part
     * @return void
     */
    public function addPart(Part $part)
    {
        $this->parts()->attach($part);
    }

    /**
     * Removes a part from the storage location
     *
     * @param Part $part
     * @return void
     */
    public function removePart(Part $part)
    {
        $this->parts()->detach($part);
    }

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
     * A storage location has many parts
     *
     * @return belongsToMany
     */
    public function parts()
    {
        return $this->belongsToMany(Part::class, 'part_storage_location', 'storage_location_id', 'part_num', 'id', 'part_num');
    }
}
