<?php

namespace App\Http\Controllers;

trait ApiHelpers
{
    /**
     * Creates heirarchy detail about a given theme
     *
     * @param array $theme
     * @param Collection $themes
     * @return \Illuminate\Support\Collection
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
            $parents = array_reverse($parents); //make top level parent first

            $theme['parents'] = $parents;
            $theme['parents_label'] = '';
            if (count($parents)) {
                foreach ($parents as $parent) {
                    if ($theme['parents_label'] != '') {
                        $theme['parents_label'] .= ' -> ';
                    }
                    $theme['parents_label'] .= $parent['name'];
                }
            } else {
                $theme['parents_label'] = 'None';
            }
        }

        return collect($theme);
    }
}
