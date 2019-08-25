<?php

use Package\Router\Router;
use App\Controllers\Controller;

/** @var Router $router */
$router->get('private', Controller::class . '@privateController');