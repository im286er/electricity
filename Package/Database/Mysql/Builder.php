<?php

namespace Package\Database\Mysql;

use Package\Env\Env;
use PDO;

class Builder
{
    /** @var Model $model */
    protected $model = null;

    protected $where = '';

    /** @var PDO $pdo */
    protected $pdo   = null;

    function __construct(Model $model)
    {
        $this->model = $model;
    }

    function update($rows)
    {
        $sql  = 'UPDATE ' . $this->model->table . ' SET';
        $exec = [];
        foreach($rows as $column as $row)
        {
            $sql    .= " $column=?";
            $exec[] =  array_merge($exec, $row);
        }
        foreach ($setData as $column => $setDatum)
        {
            $sql .= sprintf("`%s` = '%s', ", $column, $setDatum);
        }
        $sql =  substr($sql, 0, -2);
        $sql .= ' WHERE ' . substr($this->where, 0, -5);
        return $this->exec($sql);
    }

    function exec($sql)
    {
        if ($this->pdo)
        {

        }
        return $sql;
    }

    function connect($drive)
    {
        $env       = Env::env('mysql');
        $dns       = sprintf('mysql:host:%s;dbname:%s', $env['ip'], $env['database']);
        $this->pdo = new PDO($dns, $env['username'], $env['password']);
    }

    function where($column, $value, $exp = '=')
    {
        $this->where .= sprintf("`%s` %s '%s' AND ", $column, $exp, $value);
        return $this;
    }
}