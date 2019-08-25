<?php


namespace App\Handles\GlobalHandles;


class Cors
{
    function handle(\Closure $next, $par, $par2)
    {
        echo '经过了 cors 处理模块<br>';
        echo $par . '接收到了<br>';
        echo $par2 . '接收到了<br>';
        return $next();
    }
}