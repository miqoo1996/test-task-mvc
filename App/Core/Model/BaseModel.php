<?php

namespace App\Core\Model;

use App\Core\Database\ActiveRecord\ActiveRecordInterface;
use App\Core\Database\DatabaseConnection;
use App\Core\Database\DatabaseManager;
use App\Core\DI\DI;
use App\Core\Exceptions\ModelNotFoundException;

abstract class BaseModel extends BaseObject
{
    protected ActiveRecordInterface $activeRecord;

    public function getActiveRecord(): ActiveRecordInterface
    {
        return $this->activeRecord;
    }

    public function __construct(array $attributes = [])
    {
        /**
         * @var $dbDriver DatabaseConnection;
         */
        $dbDriver = DI::make(DatabaseManager::class)->getDriver();

        $this->activeRecord = $dbDriver->getActiveRecord()->setTableName($this->tableName());

        $this->setAttributes($attributes);
    }

    abstract public function tableName(): string;

    public function all()
    {
        return $this->activeRecord->findAll();
    }

    public function find(string $value, string $column = 'id')
    {
        return $this->activeRecord->find($value, $column);
    }

    public function findOrFail(string $value, string $column = 'id')
    {
        if (empty($model = $this->find($value, $column))) {
            throw new ModelNotFoundException("Model not found.");
        }

        return $model;
    }

    public function create(array $attributes = []) : self
    {
        $this->mergeAttributes($attributes);

        $this->removeAttribute('id');

        return $this->mergeAttributes(array_merge($attributes, [
            'id' => $this->activeRecord->insert($this->getAttributes())
        ]));
    }

    public function update(array $attributes = []) : self
    {
        $this->mergeAttributes($attributes);

        if (empty($id = $this->getAttribute('id'))) {
            throw new ModelNotFoundException("Model not found.");
        }

        $this->removeAttribute('id');

        return $this->mergeAttributes(array_merge($attributes, [
            'id' => $this->activeRecord->update($id, 'id', $this->getAttributes())
        ]));
    }

    public function save(array $attributes) : self
    {
        return $this->getAttribute('id') ? $this->update($attributes) : $this->create($attributes);
    }

    public function delete(string $value, string $column = 'id') : int
    {
        $this->setAttributes([]);

        return $this->activeRecord->delete($value, $column);
    }
}