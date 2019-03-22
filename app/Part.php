<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['part_num', 'name', 'part_category_id'];

    /**
     * The relationships to always eager load
     *
     * @var array
     */
    protected $with = ['category'];

    /**
     * A part belongs to a part category
     *
     * @return belongsTo
     */
    public function category()
    {
        return $this->belongsTo(PartCategory::class, 'part_category_id');
    }
}
