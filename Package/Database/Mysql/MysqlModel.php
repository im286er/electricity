<?php

namespace Package\Database\Mysql;

use Package\Collocation\Attribute;

/**
 * Class MysqlModel
 * @package Package\Database\Mysql
 *
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class MysqlModel extends Attribute {

    public $table      = null;

    public $connect    = null;

    public $primaryKey = 'id';

    static function query()
    {
        return new MysqlBuilder(new static());
    }

    protected function hasMany(MysqlBuilder $mysqlBuilder, $modelKey, $targetKey)
    {
        return $mysqlBuilder->where($targetKey, $this->$modelKey)->get();
    }

    protected function hasOne(MysqlBuilder $mysqlBuilder, $modelKey, $targetKey)
    {
        return $mysqlBuilder->where($targetKey, $this->$modelKey)->first();
    }

    function save()
    {
        $builder    = new MysqlBuilder($this);
        $attributes = $this->attributes;
        if ($primary = $this->attributes[$this->primaryKey])
        {
            unset($attributes[$this->primaryKey]);
            return $builder->where($this->primaryKey, $primary)->update($attributes);
        }
        return $builder->insert([$attributes]);
    }
}