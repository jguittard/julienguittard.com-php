<?php
/**
 * Julien Guittard API
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/apijulienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://api.julienguittard.com)
 */

namespace JG\Behat\ApiExtension\Api;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tolerance\Operation\Callback;
use Tolerance\Operation\Runner\CallbackOperationRunner;
use Tolerance\Operation\Runner\RetryOperationRunner;
use Tolerance\Waiter\SleepWaiter;
use Tolerance\Waiter\TimeOut;

/**
 * Class ApiBrowser
 *
 * @package JG\Behat\ApiExtension\Api
 */
class ApiBrowser
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var array
     */
    private $requestHeaders = [];

    /**
     * @var ResponseStorage
     */
    private $responseStorage;

    /** @var string */
    private $host;

    /**
     * @var MessageFactoryDiscovery
     */
    private $messageFactory;

    /**
     * ApiBrowser constructor.
     *
     * @param string $host
     * @param HttpClient $httpClient
     */
    public function __construct(string $host, HttpClient $httpClient = null)
    {
        $this->host = $host;
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->messageFactory = MessageFactoryDiscovery::find();
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addRequestHeader($name, $value)
    {
        if (isset($this->requestHeaders[$name])) {
            $this->requestHeaders[$name] .= ', '.$value;
        } else {
            $this->requestHeaders[$name] = $value;
        }
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setRequestHeader($name, $value)
    {
        $this->removeRequestHeader($name);
        $this->addRequestHeader($name, $value);
    }

    /**
     * Allow to override the httpClient to use yours with specific middleware for example.
     *
     * @param HttpClient $httpClient
     */
    public function useHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param ResponseStorage $responseStorage
     */
    public function enableResponseStorage(ResponseStorage $responseStorage)
    {
        $this->responseStorage = $responseStorage;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param string|array $body
     */
    public function sendRequest($method, $uri, $body = null)
    {
        if (false === $this->hasHost($uri)) {
            $uri = rtrim($this->host, '/').'/'.ltrim($uri, '/');
        }

        $this->request = $this->messageFactory->createRequest($method, $uri, $this->requestHeaders, $body);
        $this->response = $this->httpClient->sendRequest($this->request);
        $this->requestHeaders = [];

        if (null !== $this->responseStorage) {
            $this->responseStorage->writeRawContent((string) $this->response->getBody());
        }
    }

    public function sendRequestUntil($method, $uri, $body, callable $assertion, $maxExecutionTime = 10)
    {
        $runner = new RetryOperationRunner(
            new CallbackOperationRunner(),
            new TimeOut(new SleepWaiter(), $maxExecutionTime)
        );
        $apiBrowser = $this;
        $runner->run(new Callback(function () use ($apiBrowser, $method, $uri, $body, $assertion) {
            $apiBrowser->sendRequest($method, $uri, $body);
            return $assertion();
        }));
    }

    /**
     * @param string $headerName
     */
    private function removeRequestHeader($headerName)
    {
        if (array_key_exists($headerName, $this->requestHeaders)) {
            unset($this->requestHeaders[$headerName]);
        }
    }

    /**
     * @param string $uri
     *
     * @return bool
     */
    private function hasHost($uri)
    {
        return strpos($uri, '://') !== false;
    }
}
