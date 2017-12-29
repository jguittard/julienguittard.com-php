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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpExchangeFormatter
 *
 * @package JG\Behat\ApiExtension\Api
 */
class HttpExchangeFormatter
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * HttpExchangeFormatter constructor.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function formatRequest(): string
    {
        if (null === $this->request) {
            throw new \LogicException('You should send a request before printing it.');
        }

        return sprintf(
            "%s %s :\n%s%s\n",
            $this->request->getMethod(),
            $this->request->getUri(),
            $this->getRawHeaders($this->request->getHeaders()),
            $this->request->getBody()
        );
    }

    /**
     * @return string
     */
    public function formatFullExchange(): string
    {
        if (null === $this->request || null === $this->response) {
            throw new \LogicException('You should send a request and store its response before printing them.');
        }

        return sprintf(
            "%s %s :\n%s %s\n%s%s\n",
            $this->request->getMethod(),
            $this->request->getUri()->__toString(),
            $this->response->getStatusCode(),
            $this->response->getReasonPhrase(),
            $this->getRawHeaders($this->response->getHeaders()),
            $this->response->getBody()
        );
    }

    /**
     * @param array $headers
     * @return string
     */
    private function getRawHeaders(array $headers): string
    {
        $rawHeaders = '';

        foreach ($headers as $key => $value) {
            $rawHeaders .= sprintf("%s: %s\n", $key, is_array($value) ? implode(", ", $value) : $value);
        }

        $rawHeaders .= "\n";

        return $rawHeaders;
    }
}
