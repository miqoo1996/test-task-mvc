<?php

namespace App\Core\Database;

use App\Core\Utils\Config;

class DatabaseManager
{
    protected string $driver;

    public function __construct()
    {
        $this->driver = strtolower(
            Config::getInstance()->get('database', 'default', 'mysql')
        );
    }

    /**
     * @throws DatabaseNotfoundException
     */
    public function __call($name, $arguments)
    {
        return $this->getDriver()->$name(...$arguments);
    }

    /**
     * @throws DatabaseNotfoundException
     */
    public function getDriver(bool $newConnection = false) : DatabaseConnection
    {
        switch ($this->driver) {
            case 'mysql':
                return MysqlAdapter::getInstance($newConnection);
            default:
                throw new DatabaseNotfoundException("Database driver: $this->driver not found.");
        }
    }
}