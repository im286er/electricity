<?php

namespace App\Controllers;

use Exception;

class Controller
{
    function publicController()
    {
        return 'publicController';
    }

    function privateController()
    {
        $parameters = new Parameters();
        echo $parameters->name;
        echo $parameters->age;
        echo $parameters->mobile;
        echo $parameters->phone;
        return 'privateController';
    }
}