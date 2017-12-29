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

use JmesPath\Env;

/**
 * Class JsonSearcher
 *
 * @package JG\Behat\ApiExtension\Json
 */
class JsonSearcher
{
    /**
     * @param Json $json
     * @param $pathExpression
     * @return mixed|null
     */
    public function search(Json $json, $pathExpression)
    {
        return Env::search($pathExpression, $json->getRawContent());
    }
}
