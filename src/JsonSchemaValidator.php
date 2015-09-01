<?php
namespace dstotijn\yii2jsv;

use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator as JSValidator;
use Yii;
use yii\base\InvalidConfigException;
use yii\validators\Validator;

/**
 * JsonSchemaValidator validates a value against a JSON Schema file.
 *
 * The URI of the schema file must be defined via the [[schema]] property.
 *
 * @author David Stotijn <dstotijn@gmail.com>
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
    public function init()
    {
        parent::init();

        if (empty($this->schema)) {
            throw new InvalidConfigException('The "schema" property must be set.');
        }

        if (!is_string($this->schema)) {
            throw new InvalidConfigException('The "schema" property must be a string.');
        }

        if ($this->message === null) {
            $this->message = Yii::t('app', '{property}: {message}.');
        }
    }

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
                $this->addError(
                    $model,
                    $attribute,
                    $this->message,
                    [
                        'property' => $error['property'],
                        'message' => ucfirst($error['message']),
                    ]
                );
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
            return [$this->message, ['property' => $error['property'], 'message' => ucfirst($error['message'])]];
        }

        return null;
    }
}
