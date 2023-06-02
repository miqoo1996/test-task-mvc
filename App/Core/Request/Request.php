<?php

namespace App\Core\Request;

class Request extends BaseRequest
{
    public static function getCsrfToken() :? string
    {
        return $_SESSION['csrf_token'] ?? null;
    }
}