<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

$cacheConfig = [
    'config_cache_path' => 'data/config-cache.php',
];

$aggregator = new ConfigAggregator([
    \Zend\Hydrator\ConfigProvider::class,
    Zend\Db\ConfigProvider::class,
    WShafer\PSR11FlySystem\ConfigProvider::class,
    JG\ConfigProvider::class,
    Blog\ConfigProvider::class,
    Api\ConfigProvider::class,
    new ArrayProvider($cacheConfig),
    new PhpFileProvider('config/navigation.php'),
    new PhpFileProvider(realpath(__DIR__) . '/autoload/{global,local}/*.php'),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();