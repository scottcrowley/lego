<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
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
     * A set belongs to one theme
     *
     * @return belongsTo
     */
    public function theme()
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }
}
