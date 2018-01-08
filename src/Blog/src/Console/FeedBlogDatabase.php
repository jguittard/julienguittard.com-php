<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

namespace Blog\Console;

use Blog\Model\Post;
use Blog\Model\PostTable;
use DateTime;
use League\Flysystem\FilesystemInterface;
use Mni\FrontYAML\Bridge\CommonMark\CommonMarkParser;
use Mni\FrontYAML\Parser;
use Symfony\Component\Yaml\Parser as YamlParser;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\ColorInterface as Color;
use ZF\Console\Route;

/**
 * Class FeedDatabase
 *
 * @package Blog\Console
 */
class FeedBlogDatabase
{
    /**
     * @var PostTable
     */
    private $postTable;

    /**
     * @var FilesystemInterface
     */
    private $fileSystem;

    /**
     * @var string
     */
    private $postDelimiter = '<!-- MORE -->';

    /**
     * FeedBlogDatabase constructor.
     *
     * @param PostTable $postTable
     * @param FilesystemInterface $fileSystem
     */
    public function __construct(PostTable $postTable, FilesystemInterface $fileSystem)
    {
        $this->postTable = $postTable;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param Route $route
     * @param Console $console
     * @return int
     */
    public function __invoke(Route $route, Console $console) : int
    {
        $basePath    = $route->getMatchedParam('basePath');
        $postsPath   = $route->getMatchedParam('postsPath');

        $message = 'Generating blog post database';
        $length  = strlen($message);
        $width   = $console->getWidth();
        $console->write($message, Color::BLUE);

        $path = sprintf('%s/%s', realpath($basePath), ltrim($postsPath));
        $trim = strlen(realpath($basePath)) + 1;

        $parser = new Parser(null, new CommonMarkParser());
        foreach ($this->fileSystem->listMarkdown() as $postPath) {
            $document = $parser->parse(file_get_contents(sprintf('%s/%s', $path, $postPath)));
            $metadata = $document->getYAML();
            $html     = $document->getContent();
            $parts    = explode($this->postDelimiter, $html, 2);
            $excerpt  = $parts[0];
            $body     = isset($parts[1]) ? $parts[1] : '';

            $post = new Post();
            $post->exchangeArray([
                'slug' => $metadata['slug'],
                'path' => substr($path, $trim),
                'created' => $metadata['created'],
                'updated' => $metadata['updated'],
                'title' => $metadata['title'],
                'author' => $metadata['author'],
                'draft' => $metadata['draft'] ? 1 : 0,
                'public' => $metadata['public'] ? 1 : 0,
                'excerpt' => $excerpt,
                'tags' => $metadata['tags'],
            ]);

            $this->postTable->insert($post->getArrayCopy());

        }

        return $this->reportSuccess($console, $width, $length);
    }

    /**
     * Report success
     *
     * @param Console $console
     * @param int $width
     * @param int $length
     * @return int
     */
    private function reportSuccess(Console $console, int $width, int $length) : int
    {
        if (($length + 8) > $width) {
            $console->writeLine('');
            $length = 0;
        }
        $spaces = $width - $length - 8;
        $spaces = ($spaces > 0) ? $spaces : 0;
        $console->writeLine(str_repeat('.', $spaces) . '[ DONE ]', Color::GREEN);
        return 0;
    }
}
