<?php

namespace Fairy;

use Fairy\exception\RouteMethodNotAllowException;
use Fairy\exception\RouteNotFoundException;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

class Route
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * 路由
     * @var array
     */
    private $routes = [];

    /**
     * 添加路由
     * @param $httpMethod
     * @param $route
     * @param $handler
     * @return $this
     */
    public function addRoute($httpMethod, $route, $handler)
    {
        $this->routes[] = [
            'method' => strtoupper($httpMethod),
            'route' => $route,
            'handler' => $handler
        ];
        return $this;
    }

    /**
     * 注册
     */
    public function register()
    {
        $this->dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route['method'], $route['route'], $route['handler']);
            }
        });
    }

    /**
     * @param $method
     * @param $path
     * @return array
     * @throws
     */
    public function dispatch($method, $path)
    {
        $routeInfo = $this->dispatcher->dispatch($method, $path);
        if ($routeInfo[0] === Dispatcher::NOT_FOUND) {
            throw new RouteNotFoundException();
        } else if ($routeInfo[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            throw new RouteMethodNotAllowException();
        } else {
            return $routeInfo;
        }
    }
}