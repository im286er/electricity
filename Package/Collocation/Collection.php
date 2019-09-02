<?php

namespace Package\Collocation;

use Iterator;

class Collection implements Iterator
{
    /**
     * @var array $datas
     */
    protected $data;

    protected $key;

    protected $datum;

    /**
     * Collection constructor.
     * @param array $data
     */
    function __construct(array $data)
    {
        $this->data = $data;
    }

    function count()
    {
        return count($this->data);
    }

    function toArray()
    {
        return $this->data;
    }

    public function key()
    {
        return $this->key;
    }

    public function rewind()
    {
        $this->key = 0;
        $this->datum = $this->data[$this->key];
    }

    public function valid()
    {
        return isset($this->data[$this->key]);
    }

    public function next()
    {
        $this->datum = $this->data[++$this->key];
    }

    function current()
    {
        return $this->datum;
    }
}