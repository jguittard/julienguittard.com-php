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

use JG\Behat\ApiExtension\ExpectationFailed;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class WrongResponseExpectation
 *
 * @package JG\Behat\ApiExtension\Api
 */
class WrongResponseExpectation extends ExpectationFailed
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
     * WrongResponseExpectation constructor.
     *
     * @param string $message
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param \Throwable $previous|null
     */
    public function __construct(string $message, RequestInterface $request, ResponseInterface $response, \Throwable $previous = null)
    {
        $this->request = $request;
        $this->response = $response;
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return string
     */
    public function getContextText(): string
    {
        $formatter = new HttpExchangeFormatter($this->request, $this->response);
        return $formatter->formatFullExchange();
    }
}
