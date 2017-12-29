<?php
/**
 * Julien Guittard API
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/apijulienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://api.julienguittard.com)
 */

namespace JG\Behat\ApiExtension;

use Behat\Gherkin\Node\PyStringNode;
use mageekguy\atoum\asserter;
use Behat\Behat\Context\Context;
use JG\Behat\ApiExtension\Api\ApiBrowser;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ApiContext
 *
 * @package JG\Behat\ApiExtension
 */
class ApiContext implements Context
{
    /**
     * @var asserter
     */
    private $asserter;

    /**
     * @var ApiBrowser
     */
    private $apiBrowser;

    /**
     * ApiContext constructor.
     *
     * @param ApiBrowser $apiBrowser
     */
    public function __construct(ApiBrowser $apiBrowser)
    {
        $this->apiBrowser = $apiBrowser;
        $this->asserter = new asserter\generator;
    }

    /**
     * @param string $method request method
     * @param string $url    relative url
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)"$/
     */
    public function iSendARequest($method, $url)
    {
        $this->apiBrowser->sendRequest($method, $url);
    }

    /**
     * Sends HTTP request to specific URL with raw body from PyString.
     *
     * @param string       $method request method
     * @param string       $url relative url
     * @param PyStringNode $body
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with body:$/
     */
    public function iSendARequestWithBody($method, $url, PyStringNode $body)
    {
        $this->apiBrowser->sendRequest($method, $url, (string) $body);
    }

    /**
     * @Then /^(?:the )?response code should be (\d+)$/
     */
    public function theResponseCodeShouldBe($code)
    {
        $expected = intval($code);
        $actual = intval($this->getResponse()->getStatusCode());
        try {
            $this->asserter->variable($actual)->isEqualTo($expected);
        } catch (\Exception $e) {
            throw new Api\WrongResponseExpectation($e->getMessage(), $this->apiBrowser->getRequest(), $this->getResponse(), $e);
        }
    }

    /**
     * @return ResponseInterface
     */
    private function getResponse()
    {
        return $this->apiBrowser->getResponse();
    }

    /**
     * @Given /^I set "([^"]*)" header equal to "([^"]*)"$/
     */
    public function iSetHeaderEqualTo($headerName, $headerValue)
    {
        $this->apiBrowser->setRequestHeader($headerName, $headerValue);
    }

    /**
     * @Given /^I add "([^"]*)" header equal to "([^"]*)"$/
     */
    public function iAddHeaderEqualTo($headerName, $headerValue)
    {
        $this->apiBrowser->addRequestHeader($headerName, $headerValue);
    }
    /**
     * Set login / password for next HTTP authentication
     *
     * @When /^I set basic authentication with "(?P<username>[^"]*)" and "(?P<password>[^"]*)"$/
     */
    public function iSetBasicAuthenticationWithAnd($username, $password)
    {
        $authorization = base64_encode($username . ':' . $password);
        $this->apiBrowser->setRequestHeader('Authorization', 'Basic ' . $authorization);
    }

    /**
     * @Then print request and response
     */
    public function printRequestAndResponse()
    {
        $formatter = $this->buildHttpExchangeFormatter();
        echo "REQUEST:\n";
        echo $formatter->formatRequest();
        echo "\nRESPONSE:\n";
        echo $formatter->formatFullExchange();
    }

    /**
     * @Then print request
     */
    public function printRequest()
    {
        echo $this->buildHttpExchangeFormatter()->formatRequest();
    }

    /**
     * @Then print response
     */
    public function printResponse()
    {
        echo $this->buildHttpExchangeFormatter()->formatFullExchange();
    }

    /**
     * @return Api\HttpExchangeFormatter
     */
    private function buildHttpExchangeFormatter(): Api\HttpExchangeFormatter
    {
        return new Api\HttpExchangeFormatter($this->apiBrowser->getRequest(), $this->getResponse());
    }
}
