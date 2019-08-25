<?php


namespace App\Handles;


interface Handle
{
    function handle(\Closure $next);
}