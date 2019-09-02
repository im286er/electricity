<?php

namespace Package\Database\Mysql;

use Package\Collocation\Collection;
use Package\Env\Env;
use PDO;

class MysqlBuilder {

    /** @var MysqlModel null $mysqlModel */
    protected $mysqlModel      = null;

    /**
     * @var array
     */
    protected $whereExec = [];

    protected $whereSql  = '';

    protected $columns   = [];

    function __construct(MysqlModel $mysqlModel)
    {
        $this->mysqlModel = $mysqlModel;
    }

    function find($primary)
    {
        $columns = $this->columns? implode(', ', $this->columns): '*';
        $sql     = "SELECT $columns FROM" . ' ' . $this->mysqlModel->table . ' ';
        $this->where($this->mysqlModel->primaryKey, $primary);
        $data    =  $this->execSql($sql)->fetch(PDO::FETCH_ASSOC);
        $modelClass = get_class($this->mysqlModel);
        /** @var MysqlModel $mysqlModel */
        $mysqlModel = new $modelClass($data);
        return $mysqlModel;
    }

    function select($columns = [])
    {
        $this->columns = $columns;
    }

    function first()
    {
        return $this->limit(0, 1);
    }

    function limit($start = 0, $perPage = 20, $style = PDO::FETCH_ASSOC)
    {
        $column    = $this->columns? implode(', ', $this->columns): '*';
        $sql       = "SELECT $column FROM" . ' ' . $this->mysqlModel->table . ' ';
        $pdo       = $this->getPdo();
        if (count($this->whereExec))
        {
            $sql .= 'WHERE ' . $this->whereSql;
        }
        $statement = $pdo->prepare($sql . " LIMIT $start," . $perPage);
        $statement->execute($this->whereExec);
        $data      = $statement->fetchAll($style);
        return $data;
    }

    function page($page = 1, $perPage = 15, $column = '*')
    {
        $count = $this->count();
        $start = ($page - 1) * $perPage;
        if ($count > $start)
        {
            $data = $this->limit($start, $perPage);
            if ($count < $perPage)
            {
                $pageCount = 1;
            } else {
                $pageCount = (int)($count / $perPage);
                if ($count % $perPage)
                {
                    ++$pageCount;
                }
            }
            return compact('count', 'pageCount', 'page', 'perPage', 'data');
        } else {
            return null;
        }
    }

    function get()
    {
        $columns = $this->columns? implode(', ', $this->columns): '*';
        $sql     = "SELECT $columns FROM" . ' ' . $this->mysqlModel->table . ' ';
        $data    = $this->execSql($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $data? new Collection($data): $data;
    }

    function count()
    {
        $sql = 'SELECT COUNT(*) FROM' . ' ' . $this->mysqlModel->table . ' ';
        return $this->execSql($sql)->fetch(PDO::FETCH_NUM)[0];
    }

    function sum($column)
    {
        $sql = "SELECT SUM($column) FROM" . ' ' . $this->mysqlModel->table . ' ';
        return $this->execSql($sql)->fetch(PDO::FETCH_NUM)[0];
    }

    function insert($attributeItems, $columns = null)
    {
        if (is_null($columns))
        {
            $columns = array_keys($attributeItems[0]);
        }
        $columnsSql = implode(', ', $columns);
        $sql        = 'INSERT INTO' . ' ' . $this->mysqlModel->table . " ($columnsSql) VALUES ";
        $withExec   = [];
        foreach ($attributeItems as $attribute)
        {
            $withExec = array_merge($withExec, array_values($attribute));
        }
        $sql .= implode(', ', array_pad([], count($attributeItems), $this->withCompact($columns)));
        $pdo =  $this->getPdo();
        $statement = $pdo->prepare($sql);
        $statement->execute($withExec);
        return $statement->rowCount();
    }

    function delete()
    {
        $sql = 'DELETE FROM' . ' ' . $this->mysqlModel->table . ' ';
        return $this->execSql($sql, [])->rowCount();
    }

    function update($attributes)
    {
        $sql      = 'UPDATE ' . $this->mysqlModel->table . ' SET ';
        $withExec = [];
        foreach ($attributes as $column => $datum)
        {
            $sql        .= "$column=? ";
            $withExec[] =  $datum;
        }
        return $this->execSql($sql, $withExec)->rowCount();
    }

    function execSql($sql, $withExec = [])
    {
        if (count($this->whereExec))
        {
            $sql .= 'WHERE ' . $this->whereSql;
        }
        $pdo =  $this->getPdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(array_merge($withExec, $this->whereExec));
        return $statement;
    }

    function getPdo()
    {
        $env = Env::env($this->mysqlModel->connect ?? 'mysql');
        $dns = sprintf('mysql:host=%s;dbname=%s', $env['ip'], $env['database']);
        $pdo = new PDO($dns, $env['username'], $env['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
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