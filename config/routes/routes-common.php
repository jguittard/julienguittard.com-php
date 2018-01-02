<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

/** @var \Zend\Expressive\Application $app */
$app->get('/', JG\Action\HomeAction::class, 'home');
$app->get('/about', JG\Action\AboutAction::class, 'about');
$app->route('/contact', JG\Action\ContactAction::class, ['GET', 'POST'], 'contact');