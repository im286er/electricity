<?php

namespace Package\Database\Mysql;

use Package\Env\Env;
use PDO;

class Builder {

    /** @var Model null $model */
    protected $model      = null;

    /**
     * @var array
     * ['column', '=',     'datum']
     * ['column', '!=',    'datum']
     * ['column', 'in',    ['datum_1', 'datum_2']]
     * ['column', 'notIn', ['datum_1', 'datum_2']]
     */
    protected $whereExec = [];

    protected $whereSql  = '';

    function __construct(Model $model)
    {
        $this->model = $model;
    }

    function insert($attributeItems, $columns = null)
    {
        if (is_null($columns))
        {
            $columns = array_keys($attributeItems[0]);
        }
        $columnsSql = implode(', ', $columns);
        $sql        = 'INSERT INTO' . ' ' . $this->model->table . " ($columnsSql) VALUES ";
        $withExec   = [];
        foreach ($attributeItems as $attribute)
        {
            $withExec = array_merge($withExec, array_values($attribute));
        }
        $sql .= implode(', ', array_pad([], count($attributeItems), $this->withCompact($withExec)));
        return $this->execSql($sql, $withExec);
    }

    function delete()
    {
        $sql = 'DELETE FROM' . ' ' . $this->model->table . ' ';
        return $this->execSql($sql, []);
    }

    /**
     * @param $attributes
     * ['column1' => 'datum_1', 'column_2' => 'datum_2']
     *
     * @return int
     */
    function update($attributes)
    {
        $sql      = 'UPDATE ' . $this->model->table . ' SET ';
        $withExec = [];
        foreach ($attributes as $column => $datum)
        {
            $sql        .= "$column=? ";
            $withExec[] =  $datum;
        }
        return $this->execSql($sql, $withExec);
    }

    function execSql($sql, $withExec)
    {
        $sql .= $this->whereSql;
        $pdo =  $this->getPdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(array_merge($withExec, $this->whereExec));
        return $statement->rowCount();
    }

    /**
     * @return PDO
     */
    function getPdo()
    {
        $env = Env::env($this->model->connect ?? 'mysql');
        $dns = sprintf('mysql:host:%s;dbname:%s', $env['ip'], $env['database']);
        return new PDO($dns, $env['username'], $env['password']);
    }

    function where($column, $datum)
    {
        $this->whereSql    .= "$column=? ";
        $this->whereExec[] =  $datum;
        return $this;
    }

    function whereIn($column, $data)
    {
        return $this->in($column, $data);
    }

    function whereNotIn($column, $data)
    {
        return $this->in($column, $data, 'NOT');
    }

    function in($column, $data, $not = '')
    {
        $sql             =  $this->withCompact($data);
        $this->whereSql  .= "$column $not IN " . $sql;
        $this->whereExec =  array_merge($this->whereExec, $data);
        return $this;
    }

    function withCompact($data)
    {
        $sql = implode(', ', array_pad([], count($data), '?'));
        return "($sql)";
    }
}