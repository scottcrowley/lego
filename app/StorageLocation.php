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

    /**
     * remove all UserPart from this location and add them to a different location
     *
     * @param StorageLocation $newLocation
     * @return void
     */
    public function moveAllPartsTo(StorageLocation $newLocation)
    {
        $this->movePartsTo($this->parts, $newLocation);
    }

    /**
     * move an array of UserPart to a different location
     *
     * @param array|Collection $parts
     * @param StorageLocation $newLocation
     * @return int
     */
    public function movePartsTo($parts, StorageLocation $newLocation)
    {
        if (! $parts instanceof \Illuminate\Support\Collection) {
            $parts = collect($parts);
        }
        $success = 0;
        $thisParts = $this->parts;

        $parts->each(function ($part) use ($newLocation, $thisParts, &$success) {
            if (! $part instanceof \App\UserPart && $part['part_num'] != '') {
                $part = UserPart::where('part_num', $part['part_num'])->first();
            }
            if (is_null($part)) {
                return;
            }
            if ($thisParts->where('part_num', $part->part_num)->first()) {
                $part->toggleLocation($this);
            }
            $part->toggleLocation($newLocation);
            $success++;
        });
        return $success;
    }

    public function getPartCountsAttribute()
    {
        return $this->parts
            ->groupBy('part_num')
            ->map(function ($part, $key) {
                return $part->sum('quantity');
            });
    }

    public function getLocationNameAttribute()
    {
        return (! is_null($this->nickname)) ? $this->nickname : $this->name;
    }

    public function getTypeNameAttribute()
    {
        return $this->type->name;
    }
}
