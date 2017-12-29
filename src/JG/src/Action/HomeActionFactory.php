<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

namespace JG\Action;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class HomeActionFactory
 *
 * @package JG\Action
 */
class HomeActionFactory
{
    /**
     * @param ContainerInterface $container
     * @return HomeAction
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): HomeAction
    {
        return new HomeAction(
            $container->get(TemplateRendererInterface::class)
        );
    }
}
