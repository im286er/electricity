<?php

use Package\Router\Router;

include_once __DIR__ . '/../bootstrap.php';

try {
    /** @var Router $router */
    die(json_encode($router->run(), JSON_UNESCAPED_UNICODE));
} catch (Exception $exception) {
    if ($exception instanceof PDOException)
    {
        http_response_code(409);
        $errorInfo = $exception->errorInfo;
        switch ($errorInfo[0])
        {
            case 'HY093':die('参数绑定异常');
            case 23000  :die('产生重复键值');break;
            default     :die('数据库异常')  ;break;
        }
    }
    http_response_code($exception->getCode());
    die($exception->getMessage());
}