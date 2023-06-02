<?php

namespace App\Core\Database;

use App\Core\Database\ActiveRecord\MysqlActiveRecord;
use App\Core\Utils\Config;
use PDO;
use PDOException;
use Throwable;

class MysqlAdapter extends DatabaseConnection
{
    private ?PDO $connection = null;

    protected MysqlActiveRecord $activeRecord;

    public function __construct()
    {
        $this->connect();

        $this->activeRecord = new MysqlActiveRecord($this->connection);
    }

    public function connect() : void
    {
        if ($this->connection) {
            return;
        }

        $config = ((object) Config::getInstance()->get('database'))->mysql;

        $host = $config['host'] ?? null;
        $database = $config['database'] ?? null;
        $username = $config['username'] ?? null;
        $password = $config['password'] ?? null;
        $port = $config['port'] ?? "3306";

        $dsn = "mysql:host=$host;dbname=$database;port=$port;charset=utf8mb4";

        try {
            $this->connection = new PDO($dsn, $username, $password);

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        } catch (Throwable $e) {
            die("Unknown issue with DB: " . $e->getMessage());
        }
    }


    public function disconnect() : void
    {
        $this->connection = null;
    }

    public function getConnection() :? PDO
    {
        return $this->connection;
    }

    public function getActiveRecord(): MysqlActiveRecord
    {
        return $this->activeRecord;
    }
}