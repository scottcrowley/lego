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
    * label for the theme's parent hierarchy
    *
    * @var string
    */
    public $parentsLabel = '';

    /**
    * label for the entire theme's hierarchy
    *
    * @var string
    */
    public $themeLabel = '';

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
     * gets all parent themes of the current theme. Also calculates a string label representaion of the parent hierarchy
     *
     * @return array
     */
    public function getAllParents()
    {
        if (is_null($this->parent_id)) {
            return null;
        }

        $parents = [];
        $theme = $this;

        while (
            (! is_null(
                $parent = $theme->parent
            ))
        ) {
            $theme = $parents[] = $parent;
        }

        $parents = array_reverse($parents); //make top level parent first

        if (count($parents)) {
            $label = '';
            foreach ($parents as $parent) {
                if ($label != '') {
                    $label .= ' -> ';
                }
                $label .= $parent->name;
            }

            $this->parentsLabel = $label;

            $this->themeLabel = $label.' -> '.$this->name;
        }

        return $parents;
    }

    /**
     * Attribute getter for getAllParents
     *
     * @return array
     */
    public function getParentsAttribute()
    {
        return $this->getAllParents();
    }

    /**
     * Attribute getter for theme_label
     *
     * @return array
     */
    public function getThemeLabelAttribute()
    {
        $this->getAllParents();
        return $this->themeLabel;
    }
}
