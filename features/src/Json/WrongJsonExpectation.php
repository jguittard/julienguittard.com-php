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

use JG\Behat\ApiExtension\ExpectationFailed;

/**
 * Class WrongJsonExpectation
 *
 * @package JG\Behat\ApiExtension\Json
 */
class WrongJsonExpectation extends ExpectationFailed
{
    /**
     * @var Json
     */
    private $json;

    /**
     * WrongJsonExpectation constructor.
     *
     * @param string $message
     * @param Json $json
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, Json $json, \Throwable $previous = null)
    {
        $this->json = $json;
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return string
     */
    public function getContextText(): string
    {
        return $this->json->encode(true);
    }
}
