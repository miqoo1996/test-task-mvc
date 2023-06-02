<?php

namespace App\Core\Database\ActiveRecord;

use PDO;

class MysqlActiveRecord implements ActiveRecordInterface
{
    protected ?string $tableName = null;
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function setTableName(string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function findAll()
    {
        $query = "SELECT * FROM {$this->tableName}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(string $value, string $column = 'id')
    {
        $query = "SELECT * FROM {$this->tableName} WHERE $column = :{$column}";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":$column", $value);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function lastRow()
    {
        $query = "SELECT * FROM {$this->tableName} ORDER BY id DESC limit 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert(array $data) : string
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $query = "INSERT INTO {$this->tableName} ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function update(string $value, string $column, array $data) : int
    {
        $set = '';
        foreach ($data as $key => $v) {
            $set .= "{$key} = :{$key}, ";
        }
        $set = rtrim($set, ', ');

        $query = "UPDATE {$this->tableName} SET {$set} WHERE $column = :{$column}";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":$column", $value, PDO::PARAM_INT);
        foreach ($data as $key => $v) {
            $stmt->bindValue(":{$key}", $v);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(string $value, string $column = 'id') : int
    {
        $query = "DELETE FROM {$this->tableName} WHERE $column = :{$column}";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":$column", $value);
        $stmt->execute();
        return $stmt->rowCount();
    }
}