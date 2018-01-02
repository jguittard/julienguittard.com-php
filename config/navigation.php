<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

return [
    'navigation' => [
        'common' => [
            [
                'label' => 'Home',
                'route' => 'home',
            ],
            /*[
                'label' => 'Blog',
                'route' => 'blog.list',
            ],*/
            [
                'label' => 'About',
                'route' => 'about',
            ],
            [
                'label' => 'Contact',
                'route' => 'contact',
            ],
        ]
    ],
    'dependencies' => [
        'aliases' => [
            'navigation' => Zend\Navigation\Navigation::class,
        ],
        'factories'  => [
            Zend\Navigation\Navigation::class => Zend\Navigation\Service\ExpressiveNavigationAbstractServiceFactory::class,
            Zend\Navigation\Middleware\NavigationMiddleware::class => Zend\Navigation\Middleware\NavigationMiddlewareFactory::class,
        ],
        'abstract_factories' => [
            Zend\Navigation\Service\ExpressiveNavigationAbstractServiceFactory::class,
        ],
        'delegators' => [
            Zend\View\HelperPluginManager::class => [
                Zend\Navigation\View\ViewHelperManagerDelegatorFactory::class,
            ],
            'ViewHelperManager' => [
                Zend\Navigation\View\ViewHelperManagerDelegatorFactory::class,
            ],
        ],
    ],
];