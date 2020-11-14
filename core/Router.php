<?php

namespace app\core;



use app\core\exceptions\NotFoundException;

class Router
{
    public $controller;
    public $request;
    public $response;
    protected $routes = [];

    /**
     * Router constructor.
     *
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function initiateRoutes(array $routes)
    {
        foreach ($routes as $route) {
            $this->{$route[0]}($route[1], [$route[2], $route[3]]);
        }
    }

    public function resolve()
    {
        $path     = $this->request->getPath();
        $method   = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
        
        if ($callback === false) {
            throw new NotFoundException();
        }

        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        if (is_array($callback)) {
            $this->setController(new $callback[0]());
            $this->controller->action = $callback[1];
            $callback[0] = $this->getController();
            Application::$app->controller = $this->controller;

            foreach ($this->controller->getMiddlewares() as $middleware) {
                $middleware->apply();
            }
        }

        return call_user_func($callback);
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller): void
    {
        $this->controller = $controller;
    }
}
