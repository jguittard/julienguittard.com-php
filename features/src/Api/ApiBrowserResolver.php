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

use Behat\Behat\Context\Argument\ArgumentResolver;
use ReflectionClass;

/**
 * Class ApiBrowserResolver
 *
 * @package JG\Behat\ApiExtension\Api
 */
class ApiBrowserResolver implements ArgumentResolver
{
    /**
     * @var ApiBrowser
     */
    private $apiBrowser;

    /**
     * ApiBrowserResolver constructor.
     *
     * @param ApiBrowser $apiBrowser
     */
    public function __construct(ApiBrowser $apiBrowser)
    {
        $this->apiBrowser = $apiBrowser;
    }

    /**
     * Resolves context constructor arguments.
     *
     * @param ReflectionClass $classReflection
     * @param mixed[] $arguments
     *
     * @return mixed[]
     */
    public function resolveArguments(ReflectionClass $classReflection, array $arguments)
    {
        $constructor = $classReflection->getConstructor();

        if ($constructor === null) {
            return $arguments;
        }

        $parameters = $constructor->getParameters();

        foreach ($parameters as $parameter) {
            if (null !== $parameter->getClass() && $parameter->getClass()->name === 'JG\Behat\ApiExtension\Api\ApiBrowser') {
                $arguments[$parameter->name] = $this->apiBrowser;
            }
        }

        return $arguments;
    }
}
