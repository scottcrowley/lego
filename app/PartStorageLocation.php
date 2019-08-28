<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartStorageLocation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'part_storage_location';

    /**
     * no timestamps needed
     *
     * @var boolean
     */
    public $timestamps = false;
}
