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

use JG\Behat\ApiExtension\Api\ResponseStorage;

/**
 * Class JsonStorage
 *
 * @package JG\Behat\ApiExtension\Json
 */
class JsonStorage implements ResponseStorage
{
    private $rawContent;

    public function writeRawContent($rawContent)
    {
        $this->rawContent = $rawContent;
    }

    /**
     * @return Json
     */
    public function readJson(): Json
    {
        if ($this->rawContent === null) {
            throw new \LogicException('No content defined. You should use JsonStorage::writeRawContent method to inject content you want to analyze');
        }
        return new Json($this->rawContent);
    }
}
