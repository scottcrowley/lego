<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    /**
     * no timestamps needed
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * An inventory has many inventory parts
     *
     * @return hasMany
     */
    public function parts()
    {
        return $this->hasMany(InventoryPart::class);
    }

    /**
     * An inventory belongs to a set.
     *
     * @return belongsTo
     */
    public function set()
    {
        return $this->belongsTo(Set::class, 'set_num', 'set_num');
    }
}
