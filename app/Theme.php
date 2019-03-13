<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public $parentsLabel = '';

    /**
     * parent
     *
     * @param Theme $theme
     * @return Theme
     */
    public function parent($theme = null)
    {
        $parentId = (is_null($theme)) ? $this->parent_id : $theme->parent_id;

        if (is_null($parentId)) {
            return null;
        }

        return Theme::find($parentId);
    }

    public function getAllParents()
    {
        if (is_null($this->parent_id)) {
            return null;
        }

        $parents = [];
        $theme = null;

        while (
            (! is_null(
                $parent = $this->parent($theme)
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
                $label .= $parent['name'];
            }

            $this->parentsLabel = $label;
        }

        return $parents;
    }

    public function getParentsAttribute()
    {
        return $this->getAllParents();
    }
}
