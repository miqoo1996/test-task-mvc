<?php

namespace App\Helpers;

class AppHelper extends Helper
{
    public function sessionStart() : void
    {
        if (empty($_SESSION)) {
            session_start();
        }
    }

    public function enableDebugMode() : void
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public function printR(...$args) : void
    {
        echo '<pre>'; array_map('print_r', $args); echo '</pre>';

        die;
    }

    public function varDump(...$args) : void
    {
        echo '<pre>'; array_map('var_dump', $args); echo '</pre>';

        die;
    }
}