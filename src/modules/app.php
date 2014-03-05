<?php

/**
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

namespace SPABootstrap;

use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;
use mym\Auth\DoctrineAuthService;
use mym\REST\SerializedResponse;
use mym\REST\Silex\JSONUtils;
use mym\REST\Silex\RESTRoutes;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use SPABootstrap\Controllers\API\UsersController;
use SPABootstrap\Controllers\IndexController;

require __DIR__ . '/../../vendor/autoload.php';

/** @var Application $app */
$app = new Application();

// service providers
$app->register(new DoctrineServiceProvider());
$app->register(new DoctrineOrmServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());

// configuration
$app['enviroment'] = require(__DIR__ . '/../../env.php');
require_once __DIR__ . '/config.prod.php';
require_once __DIR__ . '/config.' . $app['enviroment'] . '.php';

// services

// serialized response
$app['serialized_response'] = function () use ($app) {

  $s = new SerializedResponse();
  $s->setFormat('json');
  $s->setCacheDir($app['serialized_response.cache_dir']);

  if ($app['debug']) {
    $s->setJsonOptions($s->getJsonOptions() | JSON_PRETTY_PRINT);
  }

  return $s;
};

// auth service
$app['auth'] = $app->share(function () use ($app) {
    $auth = new DoctrineAuthService();
    $auth->setConnection($app['db']);
    return $auth;
  });

// twig
$app['twig'] = $app->share($app->extend('twig', function(\Twig_Environment $twig) {

      // use custom tags to avoid conflicts with angular
      $lexer = new \Twig_Lexer($twig, array(
        'tag_variable'  => array('{{{', '}}}')
      ));

      $twig->setLexer($lexer);

      return $twig;
  }));

// annotations autoloading

// orm
AnnotationRegistry::registerAutoloadNamespace(
  'Doctrine\ORM\Mapping',
  $app['paths.root'] . '/vendor/doctrine/orm/lib'
);

// symfony
AnnotationRegistry::registerAutoloadNamespace(
  'Symfony\Component\Validator\Constraint',
  $app['paths.root'] . '/vendor/symfony/validator'
);

// jms serializer annotations
AnnotationRegistry::registerAutoloadNamespace(
  'JMS\Serializer\Annotation',
  $app['paths.root'] . '/vendor/jms/serializer/src'
);

// JSON body parsing
JSONUtils::registerJSONRequestHandling($app);

// JSON exception handler
JSONUtils::registerJSONExceptionHandler($app);

// controllers

$app['controllers.api.users'] = $app->share(function() use ($app) {
  $c = new UsersController();
  $c->setOm($app['orm.em']);
  $c->setRepository($app['orm.em']->getRepository('app:User'));
  $c->setResponse($app['serialized_response']);
  $c->setAuthService($app['auth']);
  return $c;
});

$app['controllers.index'] = $app->share(function() use ($app) {
    $c = new IndexController();
    $c->setTwig($app['twig']);
    return $c;
  });

// API routes
RESTRoutes::register(
  $app,
  'controllers.api.users',
  '/api/v1/users'
);

// other routes
$app->get('/', 'controllers.index:indexAction');




