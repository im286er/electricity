<?php

namespace Package\Env;

class Env
{
    static function env($indexItems)
    {
        $env        = json_decode(file_get_contents(__DIR__ . '/../../.env.json', 'r'), JSON_UNESCAPED_UNICODE);
        $indexItems = explode('.', $indexItems);
        foreach ($indexItems as $indexItem)
        {
            $env = $env[$indexItem];
        }
        return $env;
    }
}