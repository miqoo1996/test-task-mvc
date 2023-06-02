<?php

namespace App\Core\Database;

use App\Core\Database\ActiveRecord\ActiveRecordInterface;
use App\Core\Database\ActiveRecord\MysqlActiveRecord;
use App\Core\Utils\Traits\Singleton;

abstract class DatabaseConnection
{
    use Singleton;

    abstract public function connect() : void;

    abstract public function disconnect() : void;

    abstract public function getConnection();

    /**
     * @return ActiveRecordInterface|MysqlActiveRecord
     */
    abstract public function getActiveRecord() : ActiveRecordInterface;
}