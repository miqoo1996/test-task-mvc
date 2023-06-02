<?php

namespace App\Models\Text;

use App\Core\Model\BaseModel;

/**
 * @property integer $id
 * @property string $unique_text
 * @property string $value
 */
class Text extends BaseModel
{
    public function tableName(): string
    {
        return 'texts';
    }
}