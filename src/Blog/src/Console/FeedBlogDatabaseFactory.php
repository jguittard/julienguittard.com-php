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

use Blog\MarkdownFilter;
use Blog\Model\PostTable;
use PDO;
use Psr\Container\ContainerInterface;


/**
 * Class FeedBlogDatabaseFactory
 *
 * @package Blog\Console
 */
class FeedBlogDatabaseFactory
{
    /**
     * @param ContainerInterface $container
     * @return FeedBlogDatabase
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FeedBlogDatabase
    {
        $postTable = $container->get(PostTable::class);
        $fileSystem = $container->get('fileSystem');
        $fileSystem->addPlugin(new MarkdownFilter());
        return new FeedBlogDatabase($postTable, $fileSystem);
    }
}
