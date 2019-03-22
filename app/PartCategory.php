<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The relationships to always eager load
     *
     * @var array
     */
    protected $with = ['storageLocation'];

    /**
     * A part category belongs to a storage location
     *
     * @return belongsTo
     */
    public function storageLocation()
    {
        return $this->belongsTo(StorageLocation::class);
    }
}
