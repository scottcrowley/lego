<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StorageType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * A storage type has to many locations
     *
     * @return hasMany
     */
    public function locations()
    {
        return $this->hasMany(StorageLocation::class);
    }
}
