<?php

spl_autoload_register(function ($classname) {
    $inApp     = strpos($classname, 'App\\')     === 0;
    $inPackage = strpos($classname, 'Package\\') === 0;
    if ($inApp || $inPackage)
    {
        $classname = str_replace('\\', '/', $classname);
        include_once __DIR__ . '/' . $classname . '.' . 'php';
    }
});

use Package\Router\Router;
use Package\Router\RouterAttribute;
use App\Handles\GlobalHandles\Cors;

$router = new Router();

$routerAttribute = new RouterAttribute;
$routerAttribute->prefix = 'key';
$routerAttribute->handles = [Cors::class . '@par1,par2'];

$router->group($routerAttribute, function (Router $router)
{
    include_once __DIR__ . '/App/Routes/public.php';
    include_once __DIR__ . '/App/Routes/private.php';
});