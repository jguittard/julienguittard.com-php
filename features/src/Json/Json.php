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

use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class Json
 *
 * @package JG\Behat\ApiExtension\Json
 */
class Json
{
    /**
     * @var mixed
     */
    private $content;

    /**
     * Json constructor.
     *
     * @param mixed $content
     * @param bool $encodedAsString
     */
    public function __construct($content, $encodedAsString = true)
    {
        $this->content = true === $encodedAsString ? $this->decode((string) $content) : $content;
    }

    /**
     * @param $content
     * @return Json
     */
    public static function fromRawContent($content): self
    {
        return new static($content, false);
    }

    public function read($expression, PropertyAccessor $accessor)
    {
        if (is_array($this->content)) {
            $expression = preg_replace('/^root/', '', $expression);
        } else {
            $expression = preg_replace('/^root./', '', $expression);
        }
        // If root asked, we return the entire content
        if (strlen(trim($expression)) <= 0) {
            return $this->content;
        }
        return $accessor->getValue($this->content, $expression);
    }

    public function getRawContent()
    {
        return $this->content;
    }

    /**
     * @param bool $pretty
     * @return string
     */
    public function encode($pretty = true)
    {
        if (true === $pretty && defined('JSON_PRETTY_PRINT')) {
            return json_encode($this->content, JSON_PRETTY_PRINT);
        }

        return json_encode($this->content);
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->encode(false);
    }

    /**
     * @param $content
     * @return mixed
     * @throws \Exception
     */
    private function decode($content)
    {
        $result = json_decode($content);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(
                sprintf('The string "%s" is not valid json', $content)
            );
        }

        return $result;
    }
}
