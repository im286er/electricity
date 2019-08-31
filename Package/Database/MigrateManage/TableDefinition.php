<?php


namespace Package\Database\MigrateManage;


use Package\Database\Mysql\MysqlBuilder;
use Package\Database\Mysql\MysqlModel;

class TableDefinition
{
    function initializeState(MysqlModel $model)
    {
        $pdo = (new MysqlBuilder($model))->getPdo();
    }
}