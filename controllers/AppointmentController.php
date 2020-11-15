<?php


namespace app\controllers;


use app\core\Application;
use app\core\BaseController;
use app\core\helper\HTML;
use app\models\Appointment;
use app\models\Calendar;
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
        $params = $this->getBody();

        if ($this->isPost()) {
            $appointment = new Appointment();
            $params['citizenId'] = Application::$app->webUser->citizenId;

            $appointment->loadData($params);

            if ($appointment->validate() && $appointment->save()) {
                $this->redirect(HTML::url('/appointments', ['citizenId' => $params['citizenId']]));
            }
        }

        $lawyers = Lawyer::findAll();

        $lawyerOptions = [];
        foreach ($lawyers as $lawyer) {
            $lawyerOptions[$lawyer->lawyerId] = "$lawyer->firstName $lawyer->lastName";
        }
        return $this->render('appointments/create', [
            'lawyers' => $lawyerOptions
        ]);
    }

    public function actionBuildCalendar()
    {
        $cal    = new Calendar();
        $params = $this->getBody();

        $direction = $params['direction'] ?? 0;
        if (isset($params['prev'])) {
            $direction--;
        }
        if (isset($params['next'])) {
            $direction++;
        }

        $timestamp = time();
        switch ($direction) {
            case ($direction < 0):
                $timestamp = $cal->getLastWeek(abs($direction))['timestamp'];
                break;
            case ($direction > 0):
                $timestamp = $cal->getNextWeek(abs($direction))['timestamp'];
                break;
        }

        $bookings = [];

        $appointments = Appointment::findAllByAttributes(['lawyerId' => $params['lawyerId']]);
        foreach ($appointments as $key => $appointment) {
            $bookings[$appointment->date][] = $appointment->hour;
        }

        return Calendar::renderWeekCalendar($bookings, $direction, $timestamp, $params['lawyerId']);
    }

    public function actionCancel()
    {
        $params = $this->getBody();
        $appointment = Appointment::findByPk($params['appointmentId']);
        $citizenId = $appointment->citizenId;

        if (!$appointment->delete()) {
            $handle = fopen($_SERVER['DOCUMENT_ROOT'] .'/logs/log.txt','a+');
            fwrite($handle, 'Unable to Cancel Appointment' . PHP_EOL);
        }

        $this->redirect(HTML::url('/appointments', ['citizenId' => $citizenId]));
    }

    public function actionSchedule()
    {
        $handle = fopen($_SERVER['DOCUMENT_ROOT'] .'/logs/log.txt','a+');
        $params = $this->getBody();
        fwrite($handle, 'params' . PHP_EOL);
        fwrite($handle, print_r($params, true) . PHP_EOL);

        $appointment = Appointment::findbyPk((int)$params['appointmentId']);

        if ($this->isPost()) {
            $appointment->loadData($params);
            fwrite($handle, '$appointment' . PHP_EOL);
            fwrite($handle, print_r($appointment, true) . PHP_EOL);

            if ($appointment->validate() && $appointment->save()) {
                $this->redirect(HTML::url('/appointments', ['citizenId' => $appointment->citizenId]));
            }
        }

        $bookings = [];
        $appointments = Appointment::findAllByAttributes(['lawyerId' => $appointment->lawyerId]);
        foreach ($appointments as $key => $appointment) {
            $bookings[$appointment->date][] = $appointment->hour;
        }

        return $this->render('appointments/reschedule', [
            'bookings'    => $bookings,
            'appointment' => $appointment,
        ]);
    }
}
