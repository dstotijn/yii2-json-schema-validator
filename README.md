# Yii 2 JSON Schema Validator

[![Build Status](https://travis-ci.org/dstotijn/yii2-json-schema-validator.svg?branch=master)](https://travis-ci.org/dstotijn/yii2-json-schema-validator)
[![Code Coverage](https://scrutinizer-ci.com/g/dstotijn/yii2-json-schema-validator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/dstotijn/yii2-json-schema-validator/?branch=master)

A Yii 2 extension that provides a validation class that wraps
[JSON Schema for PHP](https://github.com/justinrainbow/json-schema).

## Installation

```
$ composer require dstotijn/yii2-json-schema-validator
```

## Usage

Model class example:

```php
<?php

namespace app\models;

use dstotijn\yii2jsv\JsonSchemaValidator;
use Yii;
use yii\base\Model;

class YourCustomModel extends Model
{
    public $json;

    public function rules()
    {
        return [
            [
                'json',
                JsonSchemaValidator::className(),
                'schema' => 'file://' . Yii::getAlias('@app/path/to/schema.json'),
                /* or URL
                'schema' => 'https://example.com/path/to/schema.json',
                */
            ],
        ];
    }
}
```

See [json-schema](http://json-schema.org) for details how to describe JSON schema.

Please view public properties in class
[JsonSchemaValidator](https://github.com/dstotijn/yii2-json-schema-validator/blob/master/src/JsonSchemaValidator.php)
to get info about all available options.
