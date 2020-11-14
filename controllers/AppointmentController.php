<?php


namespace app\controllers;


use app\core\BaseController;
use app\models\Appointment;
use app\models\Lawyer;

class AppointmentController extends BaseController
{
    public function actionList()
    {
        $params = $this->getBody() ?? [];

        $data = Appointment::findAllByAttributes($params);

        return $this->render('appointments/list', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {

        $lawyers = Lawyer::findAll();

        $lawyerOptions = [];
        foreach ($lawyers as $lawyer) {
            $lawyerOptions[$lawyer->lawyerId] = "$lawyer->firstName $lawyer->lastName";
        }
        return $this->render('appointments/create', [
            'lawyers' => $lawyerOptions
        ]);
    }

}
