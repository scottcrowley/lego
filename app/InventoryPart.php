<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryPart extends Model
{
    /**
     * no timestamps needed
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * An inventory part_num has one part
     *
     * @return hasOne
     */
    public function part()
    {
        return $this->hasOne(Part::class, 'part_num', 'part_num');
    }

    /**
     * An inventory color_id has one color
     *
     * @return hasOne
     */
    public function color()
    {
        return $this->hasOne(Color::class, 'id');
    }

    /**
     * An inventory part belongs to one inventory
     *
     * @return belongsTo
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'id');
    }
}
