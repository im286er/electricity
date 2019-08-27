<?php


namespace App\Handles\GlobalHandles;


class Cors
{
    function handle(\Closure $next, $par, $par2)
    {
        return $next();
    }
}