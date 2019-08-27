<?php


namespace Package\Validator;


use Exception;

class Parameters
{
    protected $parameters = [];

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        if (!$this->parameters[$name])
        {
            Validator::match($name);
            $this->parameters[] = $name;
        }
        return $_REQUEST[$name];
    }
}