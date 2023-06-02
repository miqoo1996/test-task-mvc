<?php

namespace App\Core\Utils;

use App\Core\Utils\Traits\Singleton;

class Config
{
    use Singleton;

    protected array $configs = [];

    protected function loadConfig(string $name) : void
    {
        if (!isset($this->configs[$name]) && file_exists($file = ROOT_DIR . '/configs/' . $name . '.php')) {
            $this->configs[$name] = require_once $file;
        }
    }

    public function get(string $name, ?string $key = null, ?string $default = null)
    {
        $this->loadConfig($name);

        return $key
            ? $this->configs[$name][$key] ?? $default
            : $this->configs[$name] ?? $default;
    }
}