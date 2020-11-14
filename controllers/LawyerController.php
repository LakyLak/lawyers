<?php


namespace app\controllers;


use app\core\Application;
use app\core\BaseController;
use app\models\Lawyer;
use app\models\LoginForm;


class LawyerController extends BaseController
{
    public function actionLogin()
    {
        $this->setLayout('auth');

        $form = New LoginForm(new Lawyer());

        if ($this->isPost()) {
            $params = $this->getBody();

            $form->loadData($params);

            if ($form->validate() && $form->login()) {
                Application::$app->session->setFlashMessage('success', 'You have been successfully logged in.');
                $this->redirect('/');
            }
        }

        return $this->render('auth/login', [
            'model' => $form,
        ]);
    }

    public function actionRegister()
    {
        $this->setLayout('auth');

        $user = New Lawyer();

        if ($this->isPost()) {
            $params = $this->getBody();

            $user->loadData($params);

            if ($user->validate() && $user->save()) {
                Application::$app->session->setFlashMessage('success', 'You have been successfully registered.');
                $this->redirect('/');
            }
        }

        return $this->render('auth/registration', [
            'model' => $user,
        ]);
    }

    public function actionLogout()
    {
        Application::$app->logout();

        $this->redirect('/');
    }

    public function actionProfile()
    {
        $params = $this->getBody();
        $user = Lawyer::findByPk($params['lawyerId']);

        if (!$user) {
            Application::$app->session->setFlashMessage('warning', 'Person does not exist.');
            $this->redirect('/');
        }

        if ($this->isPost()) {
            $user->loadData($params);

            if ($user->validate() && $user->save()) {
                Application::$app->session->setFlashMessage('success', 'Person successfully updated.');
                $this->redirect('/');
            }
        }

        return $this->render('lawyers/profile', [
            'model' => $user,
        ]);
    }
}



