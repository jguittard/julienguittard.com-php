<?php
/**
 * Julien Guittard API
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/apijulienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://api.julienguittard.com)
 */

namespace JG\Behat\ApiExtension\Json;

use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

/**
 * Class JsonSchema
 *
 * @package JG\Behat\ApiExtension\Json
 */
class JsonSchema
{
    /**
     * @var string
     */
    private $filename;

    /**
     * JsonSchema constructor.
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @param Json $json
     * @param Validator $validator
     * @param SchemaStorage $schemaStorage
     * @return bool
     * @throws \Exception
     */
    public function validate(Json $json, Validator $validator, SchemaStorage $schemaStorage): bool
    {
        $schema = $schemaStorage->resolveRef('file://' . realpath($this->filename));
        $data = $json->getRawContent();
        $validator->check($data, $schema);
        if (!$validator->isValid()) {
            $msg = "JSON does not validate. Violations:" . PHP_EOL;
            foreach ($validator->getErrors() as $error) {
                $msg .= sprintf("  - [%s] %s" . PHP_EOL, $error['property'], $error['message']);
            }
            throw new \Exception($msg);
        }
        return true;
    }
}
