<?php

function create($class, $attributes = [], $times = null)
{
    return factory($class, $times)->create($attributes);
}

function make($class, $attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
}

function makeRaw($class, $attributes = [], $times = null)
{
    return factory($class, $times)->raw($attributes);
}

function createRaw($class, $attributes = [], $times = null)
{
    $f = factory($class, $times)->create($attributes);
    return $f->toArray();
}

function checkNameExists($response, $name)
{
    $data = collect($response->getData()->data);

    return ($data->where('name', $name)->isNotEmpty());
}
