<?php

namespace Package\Router;

use App\Handles\Handle;

class Router
{
    protected $attributes   = [];

    protected $routes       = [];

    /** @var RouteAttribute $currentRoute */
    protected $currentRoute;

    function run()
    {
        $route = $this->routes[$_SERVER['REQUEST_METHOD'] . trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/')];
        if ($route)
        {
            $route = $this->currentRoute = new RouteAttribute($route);
            if ($route->handles)
            {
                return $this->sendThroughPipeline($route->handles, $this->execController());
            } else {
                return $this->execController()();
            }
        }
        return '很显然, 我们压根么的这个功能, 我不晓得你从拉个地方进到起这个地方的';
    }

    function sendThroughPipeline(array $handles, \Closure $then)
    {
        $pipeline = array_reduce(array_reverse($handles), $this->carry(), $then);
        return $pipeline();
    }

    function carry()
    {
        return function ($stack, $pipe) {
            return function () use ($stack, $pipe) {
                list($name, $parameter) = explode('@', $pipe, 2);
                $parameter = explode(',', $parameter);
                $parameter = array_merge([$stack], $parameter);
                /** @var Handle $handle */
                $handle = new $name;
                return $handle->handle(...$parameter);
            };
        };
    }

    function execController()
    {
        $route = $this->currentRoute;
        return function () use ($route) {
            list($controller, $method) = explode('@', $route->action);
            return (new $controller)->$method();
        };
    }

    function group(RouterAttribute $routerAttribute, \Closure $next)
    {
        if ($this->attributes)
        {
            $lastAttribute = end($this->attributes);
            if ($routerAttribute->prefix)
            {
                $lastAttribute->prefix =
                    trim($lastAttribute->prefix   ?? '', '/') .
                    '/' .
                    trim($routerAttribute->prefix ?? '', '/');
            }
            if ($routerAttribute->handles)
            {
                $lastAttribute->handles[] = $routerAttribute->handles;
            }
            $this->attributes[] = $lastAttribute;
        }
        if ($routerAttribute)
        {
            $this->attributes[] = $routerAttribute;
        }
        $next($this);
        array_pop($this->attributes);
    }

    function add($method, $uri, $action)
    {
        /** @var RouterAttribute $lastAttribute */
        $lastAttribute = end($this->attributes);
        if ($lastAttribute && $lastAttribute->prefix)
        {
            $uri = $lastAttribute->prefix . '/' . trim($uri, '/');
        }
        if ($lastAttribute->handles)
        {
            $handles = $lastAttribute->handles;
        }
        $this->routes[$method . trim($uri, '/')] = compact('method', 'uri', 'action', 'handles');
    }

    function get($uri, $action)
    {
        $this->add('GET', $uri, $action);
    }

    function post($uri, $action)
    {
        $this->add('POST', $uri, $action);
    }

    function put($uri, $action)
    {
        $this->add('PUT', $uri, $action);
    }

    function delete($uri, $action)
    {
        $this->add('DELETE', $uri, $action);
    }
}