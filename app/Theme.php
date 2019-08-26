<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $with = ['label'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['parents_label', 'theme_label'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['label'];

    // /**
    // * label for the theme's parent hierarchy
    // *
    // * @var string
    // */
    // public $parentsLabel = '';

    // /**
    // * label for the entire theme's hierarchy
    // *
    // * @var string
    // */
    // public $themeLabel = '';

    /**
     * A parent belongs to one theme
     *
     * @return belongsTo
     */
    public function parent($theme = null)
    {
        return $this->belongsTo(Theme::class, 'parent_id');
    }

    /**
     * A parent belongs to one theme label
     *
     * @return belongsTo
     */
    public function label()
    {
        return $this->belongsTo(ThemeLabel::class, 'id', 'theme_id');
    }

    /**
     * Getter for label->parents_label
     *
     * @return string
     */
    public function getParentsLabelAttribute()
    {
        return (is_null($this->label)) ? '' : $this->label->parents_label;
    }

    /**
     * Getter for label->theme_label
     *
     * @return string
     */
    public function getThemeLabelAttribute()
    {
        return (is_null($this->label)) ? '' : $this->label->theme_label;
    }
}
