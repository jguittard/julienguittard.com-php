<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

namespace Blog\Middleware;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class ListPostMiddlewareFactory
 *
 * @package Blog\Middleware
 */
class ListPostMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     * @return ListPostMiddleware
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ListPostMiddleware
    {
        return new ListPostMiddleware(
            $container->get(TemplateRendererInterface::class),
            $container->get('fileSystem')
        );
    }
}
