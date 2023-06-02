<?php

namespace App\Core\Request;

abstract class BaseRequest
{
    public static function clientIp() : string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    public static function getRequestMethod() :? string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? null);
    }

    public static function isRequestMethod(string $method) : bool
    {
        return self::getRequestMethod() === strtoupper($method);
    }

    public static function get(string $name, $default = null)
    {
        return $_GET[$name] ?? $default;
    }

    public static function post(string $name, $default = null)
    {
        return $_POST[$name] ?? $default;
    }

    public static function any(string $name, $default = null)
    {
        return $_REQUEST[$name] ?? $default;
    }
}