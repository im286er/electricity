<?php

namespace Package\Collocation;

class Attribute
{
    protected $attribute = [];

    function __construct($attribute = [])
    {
        $this->attribute = $attribute;
    }

    public static function __callStatic($name, $arguments)
    {
        $staticClass = static::class;
        return (new $staticClass)->$name;
    }

    function __get($name)
    {
        return $this->attribute[$name] ?? null;
    }

    function __set($name, $value)
    {
        $this->attribute[$name] = $value;
    }

    function getAttribute()
    {
        return $this->attribute;
    }

}