<?php


namespace app\core;


use app\core\middlewares\BaseMiddleware;

class BaseController
{
    public $layout = 'main';

    /**
     * @var \app\core\middlewares\BaseMiddleware[]
     */
    protected $middlewares = [];

    public function render($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }

    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }

    public function getBody()
    {
        return Application::$app->request->getBody();
    }

    public function isPost()
    {
        return Application::$app->request->getMethod() === 'post';
    }

    public function redirect($url)
    {
        return Application::$app->response->redirect($url);
    }

    public function setFlashMessage(string $type, string $message)
    {
        Application::$app->session->setFlashMessage($type, $message);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function getRequestData()
    {
        return Application::$app->request->getData();
    }
}
