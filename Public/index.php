<?php

use Package\Router\Router;

include_once __DIR__ . '/../bootstrap.php';

try {
    /** @var Router $router */
    die(json_encode($router->run(), JSON_UNESCAPED_UNICODE));
} catch (Exception $exception) {
    die($exception->getMessage());
}