<?php

namespace Package\Collocation;

class Attribute
{
    protected $attributes = [];

    function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    function getAttributes()
    {
        return $this->attributes;
    }

}