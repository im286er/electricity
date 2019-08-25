<?php


namespace App\Attributes;

use Package\Collocation\Attribute;

/**
 * Class Server
 * @package App\Attributes
 *
 * @method static SCRIPT_NAME
 * @method static REQUEST_URI
 * @method static REQUEST_METHOD
 * @method static HTTP_HOST
 * @method static argv
 * @method static argc
 */
class Server extends Attribute
{
    public function __construct()
    {
        parent::__construct($_SERVER);
    }
}