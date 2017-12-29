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

use JsonSchema\Validator;
use JsonSchema\SchemaStorage;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Uri\UriResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class JsonParser
 *
 * @package JG\Behat\ApiExtension\Json
 */
class JsonParser
{
    /**
     * @var string
     */
    private $evaluationMode;

    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * JsonParser constructor.
     *
     * @param string $evaluationMode
     */
    public function __construct(string $evaluationMode)
    {
        $this->evaluationMode = $evaluationMode;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor()
        ;
    }

    /**
     * @param Json $json
     * @param $expression
     * @return array|mixed
     * @throws \Exception
     */
    public function evaluate(Json $json, $expression)
    {
        if ($this->evaluationMode === 'javascript') {
            $expression = str_replace('->', '.', $expression);
        }
        try {
            return $json->read($expression, $this->propertyAccessor);
        } catch (\Exception $e) {
            throw new \Exception(sprintf('Failed to evaluate expression "%s"', $expression), 0, $e);
        }
    }

    /**
     * @param Json $json
     * @param JsonSchema $schema
     * @return bool
     */
    public function validate(Json $json, JsonSchema $schema): bool
    {
        return $schema->validate($json, new Validator, new SchemaStorage(new UriRetriever, new UriResolver));
    }
}
