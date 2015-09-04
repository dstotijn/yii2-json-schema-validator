<?php

namespace tests\codeception\unit\models;

use dstotijn\yii2jsv\JsonSchemaValidator;
use dstotijn\yii2jsv\tests\data\models\Car;
use dstotijn\yii2jsv\tests\data\models\CarWithoutSchema;
use yii\codeception\TestCase;

class JsonSchemaValidatorTest extends TestCase
{
    /**
     * @var string The URI of the JSON schema file to use for validation.
     */
    public $schema;

    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
        $this->schema = 'file://' . __DIR__ . '/../../../data/json_schemas/car.json';
    }

    public function testValidateValue()
    {
        $val = new JsonSchemaValidator(['schema' => $this->schema]);
        $this->assertTrue($val->validate('{"brand":"Porsche","model":"928S","year":1982}'));
    }

    public function testValidateValueSchemaEmptyInvalidConfigException()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "schema" property must be set.');
        $val = new JsonSchemaValidator;
        $val->validate('foobar');
    }

    public function testValidateValueNotStringFail()
    {
        $val = new JsonSchemaValidator(['schema' => $this->schema]);
        $val->validate(['foobar'], $error);
        $this->assertEquals('The value must be a string.', $error);
    }

    public function testValidateValueNotJsonStringFail()
    {
        $val = new JsonSchemaValidator(['schema' => $this->schema]);
        $val->validate('{]', $error);
        $this->assertEquals('The value must be a valid JSON string.', $error);
    }

    public function testValidateValuePropertyFail()
    {
        $val = new JsonSchemaValidator(['schema' => $this->schema]);
        $this->assertFalse($val->validate('{"foo":"bar"}', $error));
        $this->assertEquals('brand: The property brand is required.', $error);
    }

    public function testValidateValueRootElementFail()
    {
        $val = new JsonSchemaValidator(['schema' => $this->schema]);
        $this->assertFalse($val->validate('"foobar"', $error));
        $this->assertEquals(': String value found, but an object is required.', $error);
    }

    public function testValidateAttribute()
    {
        $car = new Car();
        $car->data = '{"brand":"Porsche","model":"928S","year":1982}';
        $this->assertTrue($car->validate());
    }

    public function testValidateAttributeSchemaEmptyInvalidConfigException()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', 'The "schema" property must be set.');
        $car = new CarWithoutSchema();
        $car->validate();
    }

    public function testValidateEmptyAttribute()
    {
        $car = new Car();
        $car->validate();
        $this->assertEquals('The value must be a string.', $car->getFirstError('data'));
    }

    public function testValidateAttributeNotStringFail()
    {
        $car = new Car();
        $car->data = ['foobar'];
        $car->validate();
        $this->assertEquals('The value must be a string.', $car->getFirstError('data'));
    }

    public function testValidateAttributeNotJsonStringFail()
    {
        $car = new Car();
        $car->data = '{]';
        $car->validate();
        $this->assertEquals('The value must be a valid JSON string.', $car->getFirstError('data'));
    }

    public function testValidateAttributePropertyFail()
    {
        $car = new Car();
        $car->data = '{"foo":"bar"}';
        $car->validate();
        $this->assertEquals('brand: The property brand is required.', $car->getFirstError('data'));
    }

    public function testValidateAttributeRootElementFail()
    {
        $car = new Car();
        $car->data = '"foobar"';
        $car->validate();
        $this->assertEquals(': String value found, but an object is required.', $car->getFirstError('data'));
    }
}
