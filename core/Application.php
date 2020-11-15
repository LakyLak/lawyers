<?php

namespace app\core;

use app\core\db\Database;
use app\core\db\DbModel;

class Application
{
    public static $root_directory;
    public $router;
    public $request;
    public $response;
    public $session;
    public $db;
    public static $app;
    public $webUser;
    public $controller;
    public $view;

    public function __construct($rootPath, array $config)
    {
//        $handle = fopen($_SERVER['DOCUMENT_ROOT'] .'/logs/log.txt','a+');
//        fwrite($handle, 'App constructor' . PHP_EOL);

        self::$root_directory = $rootPath;

        self::$app      = $this;
        $this->request  = new Request();
        $this->response = new Response();
        $this->router   = new Router($this->request, $this->response);
        $this->db       = new Database($config['db']);
        $this->session  = new Session();
        $this->view     = new View();

        $this->webUser = $this->getWebUser($config);

//        $sessionWebUserData = $this->session->get(Session::USER_KEY);
//        if ($sessionWebUserData) {
//            $webUserArray  = explode('-', $sessionWebUserData);
//            $class         = $config['webUserClasses'][$webUserArray[0]];
//            $this->webUser = $class::findByPk($webUserArray[1]);
//        }
    }

    private function getWebUser(array $config)
    {
        $sessionWebUserData = $this->session->get(Session::USER_KEY);
        if ($sessionWebUserData) {
            $webUserArray  = explode('-', $sessionWebUserData);
            $class         = $config['webUserClasses'][$webUserArray[0]];
            return $class::findByPk($webUserArray[1]);
        }

        return null;
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $handle = fopen($_SERVER['DOCUMENT_ROOT'] .'/logs/log.txt','a+');
            fwrite($handle, print_r('Application.php: ' . $e->getMessage(), true) . PHP_EOL);
            Application::$app->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', ['exception' => $e]);
        }
    }

    public function login($user)
    {
        if (!$user) {
            return false;
        }

        $this->webUser  = $user;
        $className      = get_class($user);
        $primaryKeyName = $className::getPrimaryKeyName($this->webUser::tableName());
        $primaryKey     = $this->webUser->{$primaryKeyName};

        $value = (new \ReflectionClass($user))->getShortName() . '-' . $primaryKey;
        $this->session->set(Session::USER_KEY, $value);

        return true;
    }

    public function logout()
    {
        $this->webUser = null;
        $this->session->remove(Session::USER_KEY);
    }

    public static function isGuest()
    {
        return !self::$app->webUser;
    }

    public static function getCurrentUser()
    {
        return self::$app->webUser ?? null;
    }

    public static function getClassName(): ?string
    {
        if (!self::$app->webUser) {
            return null;
        }

        return strtolower((new \ReflectionClass(self::$app->webUser))->getShortName());
    }
}
