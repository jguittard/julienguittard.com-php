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

use Behat\Behat\Context\Argument\ArgumentResolver;

/**
 * Class JsonInspectorResolver
 *
 * @package JG\Behat\ApiExtension\Json
 */
class JsonInspectorResolver implements ArgumentResolver
{
    /**
     * @var JsonInspector
     */
    private $jsonInspector;

    /**
     * JsonInspectorResolver constructor.
     *
     * @param JsonInspector $jsonInspector
     */
    public function __construct(JsonInspector $jsonInspector)
    {
        $this->jsonInspector = $jsonInspector;
    }

    /**
     * @param \ReflectionClass $classReflection
     * @param array $arguments
     * @return array
     */
    public function resolveArguments(\ReflectionClass $classReflection, array $arguments)
    {
        $constructor = $classReflection->getConstructor();

        if ($constructor === null) {
            return $arguments;
        }

        $parameters = $constructor->getParameters();

        foreach ($parameters as $parameter) {
            if (null !== $parameter->getClass() && $parameter->getClass()->name === 'JG\Behat\ApiExtension\Json\JsonInspector') {
                $arguments[$parameter->name] = $this->jsonInspector;
            }
        }

        return $arguments;
    }
}
