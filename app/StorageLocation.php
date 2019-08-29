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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['location_name'];

    /**
     * Toggles a part association in this storage location
     *
     * @param UserPart $part
     * @return void
     */
    public function togglePart(UserPart $part)
    {
        $this->parts()->toggle($part);
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
        return $this->belongsToMany(UserPart::class, 'part_storage_location', 'storage_location_id', 'part_num', 'id', 'part_num');
    }

    public function getLocationNameAttribute()
    {
        return (! is_null($this->nickname)) ? $this->nickname : $this->name;
    }
}
