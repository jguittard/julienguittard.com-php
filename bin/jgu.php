<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;
use Zend\Console\Console;
use ZF\Console\Application;

chdir(__DIR__ . '/../');
require_once 'vendor/autoload.php';

define('VERSION', '0.1.0');

$container = require 'config/container.php';

// Hack, to ensure routes are properly injected
$container->get('Zend\Expressive\Application');

$routes = [
    [
        'name' => 'feed-blog-db',
        'route' => '[--basePath=] [--postsPath=]',
        'description' => 'Re-create the blog post database from the markdown post files.',
        'short_description' => 'Generate and feed the blog post database.',
        'options_descriptions' => [],
        'defaults' => [
            'basePath'    => realpath(getcwd()),
            'postsPath'   => 'data/blog',
        ],
        'handler' => function ($route, $console) use ($container) {
            $handler = $container->get(Blog\Console\FeedBlogDatabase::class);
            return $handler($route, $console);
        }
    ],
];

$app = new Application(
    'mwop.net',
    VERSION,
    $routes,
    Console::getInstance()
);
$exit = $app->run();
exit($exit);