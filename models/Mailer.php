<?php


namespace app\models;


class Mailer
{
    const MAIL_INITIAL     = 0;
    const MAIL_NEW         = 1;
    const MAIL_RESCHEDULED = 2;
    const MAIL_CANCELED    = 3;
    const MAIL_APPROVED    = 4;
    const MAIL_REJECTED    = 5;

    public static function notify($person, int $actionType, array $data = [])
    {
        $to = $person->email;
        $subject = 'Online Registry';

        $message = sprintf(self::getMailMessage($actionType), $person->firstName);

        $mail = "
            <html>
            <head>
              <title></title>
            </head>
            <body>
              <p>$message</p>
            </body>
            </html>
            ";

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = 'From: Online Registry <online@registry.com>';

        return mail($to, $subject, $mail, implode("\r\n", $headers));
    }

    private static function getMailMessage(int $type)
    {
        switch ($type) {
            case self::MAIL_INITIAL:
                return 'Welcome %s, your appointment has been submitted.';
            case self::MAIL_NEW:
                return 'Hi %s, new appointment has been created for you.';
            case self::MAIL_RESCHEDULED:
                return 'Hi %s, your appointment has been rescheduled.';
            case self::MAIL_CANCELED:
                return 'Hi %s, your appointment has been canceled.';
            case self::MAIL_APPROVED:
                return 'Hi %s, your appointment has been approved.';
            case self::MAIL_REJECTED:
                return 'Hi %s, your appointment has been rejected. you can reschedule it.';
            default:
                return 'Hi %s, there has been change in your appointment.';
        }
    }
}
