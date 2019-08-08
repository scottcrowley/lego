<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
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
    protected $with = ['parent_theme'];

    public function parent_theme()
    {
        return $this->belongsTo(Theme::class, 'theme_id', 'id');
    }
}
