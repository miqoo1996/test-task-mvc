<?php

namespace App\Controllers;

use App\Core\DI\DI;
use App\Core\View\View;

abstract class Controller
{
    public function getView() : View
    {
        return DI::make(View::class);
    }

    public function redirect(string $url) : void
    {
        header("Location: $url"); die;
    }
}