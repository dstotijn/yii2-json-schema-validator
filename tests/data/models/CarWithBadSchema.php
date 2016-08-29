<?php

namespace dstotijn\yii2jsv\tests\data\models;

use dstotijn\yii2jsv\JsonSchemaValidator;
use yii\base\Model;

/**
 * Car model class
 */
class CarWithBadSchema extends Model
{
    /**
     * @var string A JSON string of car data.
     */
    public $data;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['data', JsonSchemaValidator::className(), 'schema' => true],
        ];
    }
}
