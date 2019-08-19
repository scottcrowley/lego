<?php

namespace App;

use Illuminate\Support\Facades\DB;
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
     * A part category has many storage locations
     *
     * @return Collection
     */
    public function storageLocations()
    {
        return DB::table('part_categories')
            ->select('part_categories.name as category', 'parts.name as part_name', 'parts.part_num', 'storage_locations.id as storage_location_id', 'storage_locations.name as location')
            ->join('parts', 'part_categories.id', '=', 'parts.part_category_id')
            ->join('part_storage_location', 'parts.part_num', '=', 'part_storage_location.part_num')
            ->join('storage_locations', 'part_storage_location.storage_location_id', '=', 'storage_locations.id')
            ->where('part_categories.id', $this->id)
            ->get()
            ->unique('location')
            ->pluck('location', 'storage_location_id');
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
