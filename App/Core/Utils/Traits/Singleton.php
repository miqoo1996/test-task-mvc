<?php

namespace App\Core\Utils\Traits;

trait Singleton
{
    protected static ?self $instance = null;

    public static function getInstance(bool $refresh = false) : self
    {
        if ($refresh === false && self::$instance) {
            return self::$instance;
        }

        return self::$instance = new static();
    }
}