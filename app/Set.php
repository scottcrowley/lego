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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['image_url'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['setImageUrl'];

    /**
     * A set belongs to one theme
     *
     * @return belongsTo
     */
    public function theme()
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }

    /**
     * A set has many set image urls
     *
     * @return hasMany
     */
    public function setImageUrl()
    {
        return $this->hasMany(SetImageUrl::class, 'set_num', 'set_num');
    }

    /**
     * Getter for image url
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        $setImageUrl = $this->setImageUrl;

        if (is_null($setImageUrl) || ! $setImageUrl->count()) {
            return '';
        }

        return $setImageUrl->first()->image_url;
    }
}
