<?php

namespace Package\Collocation;

class Collection
{
    /**
     * @var Attribute $attributes
     */
    protected $attribute;

    /**
     * Collection constructor.
     * @param Attribute $attribute
     */
    function __construct(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }

    function toArray()
    {
        return $this->attribute->getAttributes();
    }
}