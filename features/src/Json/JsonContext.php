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

use mageekguy\atoum\asserter\generator as asserter;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;

/**
 * Class JsonContext
 *
 * @package JG\Behat\ApiExtension\Json
 */
class JsonContext implements Context
{
    /**
     * @var JsonInspector
     */
    private $jsonInspector;

    /**
     * @var asserter
     */
    private $asserter;

    /**
     * @var string
     */
    private $jsonSchemaBaseUrl;

    /**
     * JsonContext constructor.
     *
     * @param JsonInspector $jsonInspector
     * @param string $jsonSchemaBaseUrl
     */
    public function __construct(JsonInspector $jsonInspector, $jsonSchemaBaseUrl = null)
    {
        $this->jsonInspector = $jsonInspector;
        $this->asserter = new asserter;
        $this->jsonSchemaBaseUrl = rtrim($jsonSchemaBaseUrl, '/');
    }

    /**
     * @When /^I load JSON:$/
     * @param PyStringNode $jsonContent
     */
    public function iLoadJson(PyStringNode $jsonContent)
    {
        $this->jsonInspector->writeJson((string) $jsonContent);
    }

    /**
     * @Then /^the response should be in JSON$/
     */
    public function responseShouldBeInJson()
    {
        $this->jsonInspector->readJson();
    }

    /**
     * @Then /^the JSON node "(?P<jsonNode>[^"]*)" should be equal to "(?P<expectedValue>.*)"$/
     * @param mixed $jsonNode
     * @param mixed $expectedValue
     */
    public function theJsonNodeShouldBeEqualTo($jsonNode, $expectedValue)
    {
        $this->assert(function () use ($jsonNode, $expectedValue) {
            $realValue = $this->evaluateJsonNodeValue($jsonNode);
            $this->asserter->variable($realValue)->isEqualTo($expectedValue);
        });
    }

    /**
     * @Then /^the JSON node "(?P<jsonNode>[^"]*)" should have (?P<expectedNth>\d+) elements?$/
     * @Then /^the JSON array node "(?P<jsonNode>[^"]*)" should have (?P<expectedNth>\d+) elements?$/
     * @param mixed $jsonNode
     * @param mixed $expectedNth
     */
    public function theJsonNodeShouldHaveElements($jsonNode, $expectedNth)
    {
        $this->assert(function () use ($jsonNode, $expectedNth) {
            $realValue = $this->evaluateJsonNodeValue($jsonNode);
            $this->asserter->phpArray($realValue)->hasSize($expectedNth);
        });
    }

    /**
     * @Then /^the JSON array node "(?P<jsonNode>[^"]*)" should contain "(?P<expectedValue>.*)" element$/
     * @param mixed $jsonNode
     * @param mixed $expectedValue
     */
    public function theJsonArrayNodeShouldContainElements($jsonNode, $expectedValue)
    {
        $this->assert(function () use ($jsonNode, $expectedValue) {
            $realValue = $this->evaluateJsonNodeValue($jsonNode);
            $this->asserter->phpArray($realValue)->contains($expectedValue);
        });
    }

    /**
     * @Then /^the JSON array node "(?P<jsonNode>[^"]*)" should not contain "(?P<expectedValue>.*)" element$/
     * @param mixed $jsonNode
     * @param mixed $expectedValue
     */
    public function theJsonArrayNodeShouldNotContainElements($jsonNode, $expectedValue)
    {
        $this->assert(function () use ($jsonNode, $expectedValue) {
            $realValue = $this->evaluateJsonNodeValue($jsonNode);
            $this->asserter->phpArray($realValue)->notContains($expectedValue);
        });
    }

    /**
     * @Then /^the JSON node "(?P<jsonNode>[^"]*)" should contain "(?P<expectedValue>.*)"$/
     * @param mixed $jsonNode
     * @param mixed $expectedValue
     */
    public function theJsonNodeShouldContain($jsonNode, $expectedValue)
    {
        $this->assert(function () use ($jsonNode, $expectedValue) {
            $realValue = $this->evaluateJsonNodeValue($jsonNode);
            $this->asserter->string((string) $realValue)->contains($expectedValue);
        });
    }

    /**
     * Checks, that given JSON node does not contain given value
     *
     * @Then /^the JSON node "(?P<jsonNode>[^"]*)" should not contain "(?P<unexpectedValue>.*)"$/
     * @param mixed $jsonNode
     * @param mixed $unexpectedValue
     */
    public function theJsonNodeShouldNotContain($jsonNode, $unexpectedValue)
    {
        $this->assert(function () use ($jsonNode, $unexpectedValue) {
            $realValue = $this->evaluateJsonNodeValue($jsonNode);
            $this->asserter->string((string) $realValue)->notContains($unexpectedValue);
        });
    }
    /**
     * Checks, that given JSON node exist
     *
     * @Given /^the JSON node "(?P<jsonNode>[^"]*)" should exist$/
     * @param mixed $jsonNode
     */
    public function theJsonNodeShouldExist($jsonNode)
    {
        try {
            $this->evaluateJsonNodeValue($jsonNode);
        } catch (\Exception $e) {
            throw new WrongJsonExpectation(sprintf("The node '%s' does not exist.", $jsonNode), $this->readJson(), $e);
        }
    }

    /**
     * Checks, that given JSON node does not exist
     *
     * @Given /^the JSON node "(?P<jsonNode>[^"]*)" should not exist$/
     * @param mixed $jsonNode
     */
    public function theJsonNodeShouldNotExist($jsonNode)
    {
        $e = null;
        try {
            $realValue = $this->evaluateJsonNodeValue($jsonNode);
        } catch (\Exception $e) {
            // If the node does not exist an exception should be throwed
        }
        if ($e === null) {
            throw new WrongJsonExpectation(
                sprintf("The node '%s' exists and contains '%s'.", $jsonNode, json_encode($realValue)),
                $this->readJson(),
                $e
            );
        }
    }

    /**
     * @Then /^the JSON should be valid according to this schema:$/
     */
    public function theJsonShouldBeValidAccordingToThisSchema(PyStringNode $jsonSchemaContent)
    {
        $tempFilename = tempnam(sys_get_temp_dir(), 'rae');
        file_put_contents($tempFilename, $jsonSchemaContent);
        $this->assert(function () use ($tempFilename) {
            $this->jsonInspector->validateJson(
                new JsonSchema($tempFilename)
            );
        });
        unlink($tempFilename);
    }

    /**
     * @Then /^the JSON should be valid according to the schema "(?P<filename>[^"]*)"$/
     */
    public function theJsonShouldBeValidAccordingToTheSchema($filename)
    {
        $filename = $this->resolveFilename($filename);
        $this->assert(function () use ($filename) {
            $this->jsonInspector->validateJson(
                new JsonSchema($filename)
            );
        });
    }

    /**
     * @Then /^the JSON should be equal to:$/
     * @param PyStringNode $jsonContent
     */
    public function theJsonShouldBeEqualTo(PyStringNode $jsonContent)
    {
        $realJsonValue = $this->readJson();
        try {
            $expectedJsonValue = new Json($jsonContent);
        } catch (\Exception $e) {
            throw new \Exception('The expected JSON is not a valid');
        }
        $this->assert(function () use ($realJsonValue, $expectedJsonValue) {
            $this->asserter->castToString($realJsonValue)->isEqualTo((string) $expectedJsonValue);
        });
    }

    /**
     * @Then the JSON path expression :pathExpression should be equal to json :expectedJson
     * @param mixed $pathExpression
     * @param mixed $expectedJson
     */
    public function theJsonPathExpressionShouldBeEqualToJson($pathExpression, $expectedJson)
    {
        $expectedJson = new Json($expectedJson);
        $actualJson = Json::fromRawContent($this->jsonInspector->searchJsonPath($pathExpression));
        $this->asserter->castToString($actualJson)->isEqualTo((string) $expectedJson);
    }

    /**
     * @Then the JSON path expression :pathExpression should be equal to:
     * @param mixed $pathExpression
     * @param PyStringNode $expectedJson
     */
    public function theJsonExpressionShouldBeEqualTo($pathExpression, PyStringNode $expectedJson)
    {
        $this->theJsonPathExpressionShouldBeEqualToJson($pathExpression, (string) $expectedJson);
    }

    /**
     * @Then the JSON path expression :pathExpression should have result
     * @param mixed $pathExpression
     */
    public function theJsonPathExpressionShouldHaveResult($pathExpression)
    {
        $json = $this->jsonInspector->searchJsonPath($pathExpression);
        $this->asserter->variable($json)->isNotNull();
    }

    /**
     * @Then the JSON path expression :pathExpression should not have result
     * @param mixed $pathExpression
     */
    public function theJsonPathExpressionShouldNotHaveResult($pathExpression)
    {
        $json = $this->jsonInspector->searchJsonPath($pathExpression);
        $this->asserter->variable($json)->isNull();
    }

    /**
     * @param $jsonNode
     * @return array|mixed
     */
    private function evaluateJsonNodeValue($jsonNode)
    {
        return $this->jsonInspector->readJsonNodeValue($jsonNode);
    }

    /**
     * @return Json
     */
    private function readJson()
    {
        return $this->jsonInspector->readJson();
    }

    /**
     * @param $filename
     * @return bool|string
     */
    private function resolveFilename($filename)
    {
        if (true === is_file($filename)) {
            return realpath($filename);
        }

        if (null === $this->jsonSchemaBaseUrl) {
            throw new \RuntimeException(sprintf(
                'The JSON schema file "%s" doesn\'t exist',
                $filename
            ));
        }

        $filename = $this->jsonSchemaBaseUrl . '/' . $filename;

        if (false === is_file($filename)) {
            throw new \RuntimeException(sprintf(
                'The JSON schema file "%s" doesn\'t exist',
                $filename
            ));
        }

        return realpath($filename);
    }

    /**
     * @param callable $assertion
     * @throws WrongJsonExpectation
     */
    private function assert(callable $assertion)
    {
        try {
            $assertion();
        } catch (\Exception $e) {
            throw new WrongJsonExpectation($e->getMessage(), $this->readJson(), $e);
        }
    }
}
