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

    public function storageLocation()
    {
        return $this->belongsToMany(StorageLocation::class);
    }
}
