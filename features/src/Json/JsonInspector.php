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

/**
 * Class JsonInspector
 *
 * @package JG\Behat\ApiExtension\Json
 */
class JsonInspector
{
    /**
     * @var JsonParser
     */
    private $jsonParser;

    /**
     * @var JsonStorage
     */
    private $jsonStorage;

    /**
     * @var JsonSearcher
     */
    private $jsonSearcher;

    /**
     * JsonInspector constructor.
     *
     * @param JsonStorage $jsonStorage
     * @param JsonParser $jsonParser
     * @param JsonSearcher $jsonSearcher
     */
    public function __construct(JsonStorage $jsonStorage, JsonParser $jsonParser, JsonSearcher $jsonSearcher)
    {
        $this->jsonParser = $jsonParser;
        $this->jsonStorage = $jsonStorage;
        $this->jsonSearcher = $jsonSearcher;
    }

    /**
     * @param $jsonNodeExpression
     * @return array|mixed
     */
    public function readJsonNodeValue($jsonNodeExpression)
    {
        return $this->jsonParser->evaluate(
            $this->readJson(),
            $jsonNodeExpression
        );
    }

    /**
     * @param $pathExpression
     * @return mixed|null
     */
    public function searchJsonPath($pathExpression)
    {
        return $this->jsonSearcher->search($this->readJson(), $pathExpression);
    }

    /**
     * @param JsonSchema $jsonSchema
     */
    public function validateJson(JsonSchema $jsonSchema)
    {
        $this->jsonParser->validate(
            $this->readJson(),
            $jsonSchema
        );
    }

    /**
     * @return Json
     */
    public function readJson()
    {
        return $this->jsonStorage->readJson();
    }

    /**
     * @param $jsonContent
     */
    public function writeJson($jsonContent)
    {
        $this->jsonStorage->writeRawContent($jsonContent);
    }
}
