<?php

/**
 * Production config
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

namespace SPABootstrap;

// misc

$app['project'] = 'SPABootstrap';
$app['version'] = '1.0.0';

$app['paths.root'] = __DIR__ . '/../..';
$app['paths.data'] = __DIR__ . '/../../data';
$app['paths.temp'] = __DIR__ . '/../../data/temp';
$app['paths.logs'] = __DIR__ . '/../../data/logs';
$app['paths.templates'] = __DIR__ . '/../../src/templates';

// services

$app['serialized_response.cache_dir'] = $app['paths.temp'] . '/serialized-response';

// Doctrine DBAL

$app['db.options'] = array(
  'dbname' => 'SPABootstrap',
  'user' => 'user',
  'password' => 'password',
  'host' => 'hostname',
  'driver' => 'pdo_mysql'
);

// Doctrine ORM

$app['orm.proxies_dir'] = $app['paths.temp'] . '/doctrine-proxies';
$app['orm.default_cache'] = 'apc';
$app['orm.auto_generate_proxies'] = false;
$app['orm.em.options'] = array(
  'mappings' => array(
    array(
      'type' => 'annotation',
      'namespace' => 'SPABootstrap\Entities',
      'path' => $app['paths.root'] . '/src/modules/SPABootstrap/Entities',
      'use_simple_annotation_reader' => false,
      'alias' => 'app'
    )
  )
);

// Twig

$app['twig.path'] = $app['paths.templates'];
$app['twig.options'] = array(
  'cache' => $app['paths.temp'] . '/twig'
);