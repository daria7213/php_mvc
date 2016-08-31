<?php
namespace Core;
/**
 * Router
 */
class Router {
  /**
   * routing table
   * @var array
   */
  protected $routes = [];

  /**
   * params from the matched router
   * @var array
   */
  protected $params = [];

  /**
   * Add a route
   * @param string $route  route url
   * @param array $params params (controller, action,..)
   */
  public function add($route, $params = []) {

    //escape slashes
    $route = preg_replace('/\//', '\\/', $route);

    //convert variables, {controller}
    $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
    //convert {id:\d+}
    $route = preg_replace('/\{([a-z]+):([^\}]+)\}/','(?P<\1>\2)', $route);
    $route = '/^' . $route . '$/i';

    $this->routes[$route] = $params;
  }
  /**
   * get all routes
   * @return array
   */
  public function getRoutes() {
    return $this->routes;
  }

  /**
   * match the url to route
   * @param  string $url
   * @return boolean true if found
   */
  public function match($url) {
    foreach ($this->routes as $route => $params) {
      if (preg_match($route, $url, $matches)) {
          foreach ($matches as $key => $match) {
            if (is_string($key)){
              $params[$key] = $match;
            }
          }
          $this->params = $params;
          return true;
      }
    }
    return false;
  }

  public function getParams() {
    return $this->params;
  }

  public function dispatch($url) {
    $url = $this->removeQueryVariables($url);

    if($this->match($url)){
      $controller = $this->params['controller'];

      $controller = $this->convertToStudlyCaps($controller);
      $controller = $this->getNamespace() . $controller . 'Controller';
      if(class_exists($controller)) {
        $controller_object = new $controller($this->params);

        if(isset($this->params['action'])){
          $action = $this->params['action'];
        } else {
          $action = 'index';
        }

        $action = $this->convertToCamelCase($action);

        if(is_callable([$controller_object, $action])) {
          $controller_object->$action(['url'=>'/'.$url]);
        } else {
            throw new \Exception("Method $action not found in controller $controller");
        }
      } else {
            throw new \Exception("Controller $controller not found");
      }
    } else {
        throw new \Exception("No route matched", 404);
    }
  }

  public function convertToStudlyCaps($string){
    return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
  }

  public function convertToCamelCase($string) {
    return lcfirst($this->convertToStudlyCaps($string));
  }

  public function removeQueryVariables($url) {
    if($url != '') {
      $parts = explode('&', $url, 2);

      if (strpos($parts[0], '=') === false) {
        $url = $parts[0];
      } else {
        $url = '';
      }
    }
    return $url;
  }

  public function getNamespace(){
    $namespace = 'App\Controllers\\';

    if(array_key_exists('namespace', $this->params)){
      $namespace .= $this->params['namespace'] . '\\';
    }

    return $namespace;
  }
}
