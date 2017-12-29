<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\RouterInterface;

return [
    'dependencies' => [
        'invokables' => [
            RouterInterface::class => FastRouteRouter::class,
        ],
    ],
    'router' => [
        'fastroute' => [
            'cache_enabled' => true,
            'cache_file'    => 'data/cache/fast-route.php',
        ],
    ],
];
