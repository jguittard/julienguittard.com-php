<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

namespace Blog;

use League\Flysystem\Plugin\AbstractPlugin;

/**
 * Class MarkdownFilter
 *
 * @package Blog
 */
class MarkdownFilter extends AbstractPlugin
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'listMarkdown';
    }

    /**
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public function handle($directory = '', bool $recursive = true): array
    {
        $result = [];
        $contents = $this->filesystem->listContents($directory, $recursive);

        /** @var  $object */
        foreach ($contents as $object) {
            if ($object['type'] == 'file' && $object['extension'] == 'md') {
                $result[] = $object['path'];
            }
        }

        return $result;
    }
}
