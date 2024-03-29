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
use Blog\Console\FeedBlogDatabase;
use Blog\Console\FeedBlogDatabaseFactory;
use Blog\Middleware\ListPostMiddleware;
use Blog\Middleware\ListPostMiddlewareFactory;
use Zend\Db\ResultSet\ResultSetInterface;

/**
 * Class ConfigProvider
 *
 * @package Blog
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                ListPostMiddleware::class => ListPostMiddlewareFactory::class,
                ResultSetInterface::class => Model\PostResultSetFactory::class,
                Model\PostTable::class => Model\PostTableFactory::class,
                FeedBlogDatabase::class => FeedBlogDatabaseFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getTemplates()
    {
        return [
            'paths' => [
                'blog' => [ __DIR__ . '/../templates/blog'],
                'widgets'    => [__DIR__ . '/../templates/widgets'],
            ],
        ];
    }
}
