<?php


namespace app\controllers;

use app\core\BaseController;
use app\core\Application;
use app\core\db\DbModel;
use app\core\helper\HTML;

class HomeController extends BaseController
{
    public function actionHome()
    {
        $webUser = Application::getCurrentUser();

        $primaryKey = null;
        if ($webUser) {
            $primaryKey = DbModel::getPrimaryKeyName($webUser::tableName());

            return $this->redirect(HTML::url('/appointments', [$primaryKey => $webUser->{$primaryKey}]));
        }

        return $this->render('home');
    }
}
