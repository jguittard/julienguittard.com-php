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

/**
 * Interface ResponseStorage
 *
 * @package JG\Behat\ApiExtension\Api
 */
interface ResponseStorage
{
    public function writeRawContent($rawContent);
}
