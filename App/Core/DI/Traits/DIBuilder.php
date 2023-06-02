<?php

namespace App\Core\DI\Traits;

trait DIBuilder
{
    /**
     * @var array
     */
    public static array $DI = [];

    /**
     * @param string $class
     * @return mixed
     */
    public static function make(string $class)
    {
        if (isset(self::$DI[$class])) return self::$DI[$class];

        $instance = new $class;

        return self::$DI[$class] = $instance;
    }

    /**
     * @param string $class
     * @return mixed
     * @throws \Exception
     */
    public static function get(string $class)
    {
        if (empty(self::$DI[$class])) {
            throw new \Exception(sprintf('DI Class %s does not exist.', self::$DI[$class]));
        }

        return self::$DI[$class];
    }
}