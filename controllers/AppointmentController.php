<?php


namespace app\controllers;


use app\core\Application;
use app\core\BaseController;
use app\core\helper\HTML;
use app\models\Appointment;
use app\models\Calendar;
use app\models\Lawyer;
use app\models\Mailer;

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
            $appointment         = new Appointment();
            $params['citizenId'] = Application::$app->webUser->citizenId;
            $params['status']    = Appointment::STATUS_NEW;

            $appointment->loadData($params);

            if ($appointment->validate() && $appointment->save()) {
                Mailer::notify($appointment->citizen,Mailer::MAIL_INITIAL);
                Mailer::notify($appointment->lawyer,Mailer::MAIL_NEW);

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

        if (isset($params['appointmentId'])) {
            return Calendar::renderWeekCalendar($bookings, $direction, $timestamp, $params['lawyerId'], true, $params['appointmentId']);
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
        Mailer::notify($appointment->citizen,Mailer::MAIL_CANCELED);
        Mailer::notify($appointment->lawyer,Mailer::MAIL_CANCELED);

        $this->redirect(HTML::url('/appointments', ['citizenId' => $citizenId]));
    }

    public function actionSchedule()
    {
        $params = $this->getBody();
        $appointment = Appointment::findbyPk((int)$params['appointmentId']);

        if ($this->isPost()) {
            $appointment->loadData($params);

            if ($appointment->validate() && $appointment->save()) {
                Mailer::notify($appointment->citizen,Mailer::MAIL_RESCHEDULED);
                Mailer::notify($appointment->lawyer,Mailer::MAIL_RESCHEDULED);

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

    public function actionApprove()
    {
        $params = $this->getBody();
        $appointment = Appointment::findByPk($params['appointmentId']);

        $appointment->status = Appointment::STATUS_APPROVED;

        if ($appointment->validate() && $appointment->save()) {
            Mailer::notify($appointment->citizen,Mailer::MAIL_APPROVED);

            $this->redirect(HTML::url('/appointments', ['lawyerId' => $appointment->lawyerId]));
        }
    }

    public function actionReject()
    {
        $params = $this->getBody();
        $appointment = Appointment::findByPk($params['appointmentId']);

        $appointment->status = Appointment::STATUS_REJECTED;

        if ($appointment->validate() && $appointment->save()) {
            Mailer::notify($appointment->citizen,Mailer::MAIL_REJECTED);

            $this->redirect(HTML::url('/appointments', ['lawyerId' => $appointment->lawyerId]));
        }
    }
}
