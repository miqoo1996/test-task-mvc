<?php

namespace App\Core\Database\ActiveRecord;

interface ActiveRecordInterface
{
    public function findAll();

    public function find(string $value, string $column = 'id');

    public function insert(array $data) : string;

    public function update(string $value, string $column, array $data) : int;

    public function delete(string $value, string $column = 'id') : int;
}