<?php

namespace Package\Database\Mysql;

use Package\Collocation\Attribute;
use Exception;
use PDO;

class Model extends Attribute {

    public $table      = null;

    public $connect    = null;

    public $primaryKey = 'id';

    function save()
    {
        $builder    = new Builder($this);
        $attributes = $this->attributes;
        if ($primary = $this->attributes[$this->primaryKey])
        {
            unset($attributes[$this->primaryKey]);
            return $builder->where($this->primaryKey, $primary)->update($attributes);
        }
        return $builder->insert([$attributes]);
    }
}