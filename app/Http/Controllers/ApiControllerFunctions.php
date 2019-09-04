<?php

namespace App\Http\Controllers;

trait ApiControllerFunctions
{
    /**
     * Sets append properties and eager loading relationship on a given page
     *
     * @param mixed $parts
     * @param null|array|string $loads
     * @param null|array|string $appends
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    protected function processPartPages($parts, $loads = null, $appends = null)
    {
        $page = $parts->paginate($this->defaultPerPage);

        if (! is_null($loads) && ! is_array($loads)) {
            $page->load($loads);
        } elseif (! is_null($loads)) {
            foreach ($loads as $load) {
                $page->load($load);
            }
        }

        if (! is_null($appends)) {
            foreach ($page->items() as $key => $part) {
                if (is_array($appends)) {
                    $page->items()[$key]->append($appends);
                    continue;
                }

                foreach ($appends as $append) {
                    $page->items()[$key]->append($append);
                }
            }
        }

        return $page;
    }
}
