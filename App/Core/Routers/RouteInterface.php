<?php

namespace App\Core\Routers;

interface RouteInterface
{
    /**
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, array $arguments);
}