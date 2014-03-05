<?php

/**
 * Development config
 * Overrides settings in production config
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

namespace SPABootstrap;

// misc
$app['debug'] = true;

// Doctrine DBAL
$app['db.options'] = array(
  'dbname' => 'SPABootstrap',
  'user' => 'root',
  'password' => 'pass',
  'host' => 'host',
  'driver' => 'pdo_mysql'
);

// Doctrine ORM
$app['orm.default_cache'] = 'array';
$app['orm.auto_generate_proxies'] = true;

// Twig
$app['twig.options'] = array_merge($app['twig.options'], array(
    'debug' => true
  ));