<?php
namespace dstotijn\yii2jsv;

use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator as JSValidator;
use yii\validators\Validator;

/**
 * Class JsonSchemaValidator
 */
class JsonSchemaValidator extends Validator
{
    /**
     * @var string The URI of the JSON schema file.
     */
    public $schema;

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = json_decode($model->$attribute);

        $retriever = new UriRetriever();
        $schema = $retriever->retrieve($this->schema);

        $validator = new JSValidator();
        $validator->check($value, $schema);

        if (!$validator->isValid()) {
            foreach ($validator->getErrors() as $error) {
                $this->addError($model, $attribute, sprintf("%s: %s.", $error['property'], $error['message']));
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        $value = json_decode($value);

        $retriever = new UriRetriever();
        $schema = $retriever->retrieve($this->schema);

        $validator = new JSValidator();
        $validator->check($value, $schema);

        if (!$validator->isValid()) {
            $error = reset($validator->getErrors());
            return ['{property}: {message}.', ['property' => $error['property'], 'message' => $error['message']]];
        }

        return null;
    }
}
