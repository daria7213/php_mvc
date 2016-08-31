<?php
session_start();
// Front controller

// require '../app/controllers/Posts.php';
// require '../core/Router.php';

require_once dirname(__DIR__) . '/vendor/autoload.php';

spl_autoload_register( function($class){
  $root = dirname(__DIR__);
  $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
  if(is_readable($file)) {
    require $root . '/' . str_replace('\\', '/', $class) . '.php';
  }
});

/**
 * Error, exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::ExceptionHandler');

/**
 * Routing
 */
$router = new Core\Router();
$router->add('', ['controller' => 'Home', 'action' => 'index']);
//$router->add('register',['controller'=>'Registration', 'action'=>'index']);
$router->add('{controller}/{action}');
$router->add('{controller}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

$_POST['lol'] = 'popo';
//echo "<pre>";
//var_dump($router->getRoutes());
//echo "</pre>";
$url = $_SERVER['QUERY_STRING'];
$router->dispatch($url);
