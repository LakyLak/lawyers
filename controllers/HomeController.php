<?php


namespace app\controllers;

use app\core\BaseController;

class HomeController extends BaseController
{
    public function actionHome()
    {
        return $this->render('home');
    }
}
