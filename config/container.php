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

$config = require __DIR__ . '/config.php';

$container = new ServiceManager();
(new Config($config['dependencies']))->configureServiceManager($container);

$container->setService('config', $config);

return $container;