<?php

namespace App\Http\Controllers;

trait RebrickableApiHelpers
{
    /**
     * themeParentHierarchy
     *
     * @param array $theme
     * @param Collection $themes
     * @return array
     */
    protected function themeParentHierarchy($theme, $themes)
    {
        if (count($theme)) {
            $parents = [];
            $parentId = $theme['parent_id'];

            while (
                ! is_null(
                    $parent = $themes->where('id', $parentId)->first()
                )
            ) {
                $parents[] = $parent;
                $parentId = $parent['parent_id'];
            }
            krsort($parents); //make top level parent first

            $theme['parents'] = $parents;
        }

        return $theme;
    }
}
