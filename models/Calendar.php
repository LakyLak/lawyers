<?php


namespace app\models;


use app\core\View;

class Calendar
{
    const WEEK_DAY_MONDAY    = 1;
    const WEEK_DAY_TUESDAY   = 2;
    const WEEK_DAY_WEDNESDAY = 3;
    const WEEK_DAY_THURSDAY  = 4;
    const WEEK_DAY_FRIDAY    = 5;
    const WEEK_DAY_SATURDAY  = 6;
    const WEEK_DAY_SUNDAY    = 7;

    const MONTH_JANUARY   = 1;
    const MONTH_FEBRUARY  = 2;
    const MONTH_MARCH     = 3;
    const MONTH_APRIL     = 4;
    const MONTH_MAY       = 5;
    const MONTH_JUNE      = 6;
    const MONTH_JULY      = 7;
    const MONTH_AUGUST    = 8;
    const MONTH_SEPTEMBER = 9;
    const MONTH_OCTOBER   = 10;
    const MONTH_NOVEMBER  = 11;
    const MONTH_DECEMBER  = 12;

    const DAY_IN_SECONDS  = 24 * 60 * 60;
    const WEEK_IN_SECONDS = 7 * 24 * 60 * 60;

    public static $daysOfWeek = [
        self::WEEK_DAY_MONDAY    => 'Monday',
        self::WEEK_DAY_TUESDAY   => 'Tuesday',
        self::WEEK_DAY_WEDNESDAY => 'Wednesday',
        self::WEEK_DAY_THURSDAY  => 'Thursday',
        self::WEEK_DAY_FRIDAY    => 'Friday',
        self::WEEK_DAY_SATURDAY  => 'Saturday',
        self::WEEK_DAY_SUNDAY    => 'Sunday',
    ];

    public static $monthNames = [
        self::MONTH_JANUARY   => 'January',
        self::MONTH_FEBRUARY  => 'February',
        self::MONTH_MARCH     => 'March',
        self::MONTH_APRIL     => 'April',
        self::MONTH_MAY       => 'May',
        self::MONTH_JUNE      => 'June',
        self::MONTH_JULY      => 'July',
        self::MONTH_AUGUST    => 'August',
        self::MONTH_SEPTEMBER => 'September',
        self::MONTH_OCTOBER   => 'October',
        self::MONTH_NOVEMBER  => 'November',
        self::MONTH_DECEMBER  => 'December',
    ];

    public function createCalendar()
    {

    }

    public static function renderWeekCalendar(array $bookings, int $direction, int $timestamp, int $lawyerId = null, bool $edit = false, int $appointmentId = null)
    {
        $view = new View();
        return $view->renderPartial('calendar/weekCalendar', [
            'bookings'      => $bookings,
            'direction'     => $direction,
            'timestamp'     => $timestamp,
            'lawyerId'      => $lawyerId,
            'reschedule'    => $edit,
            'appointmentId' => $appointmentId,
        ]);
    }

    public function getMonthInfo($timestamp = null)
    {
        if (!$timestamp) {
            $timestamp = time();
        }

        return [
            'todayDate'    => date('Y-m-d', $timestamp),
            'monthNo'      => date('m'),
            'monthName'    => self::$monthNames[date('m')],
            'firstDayDate' => date('Y-m-d', strtotime(date('1-m-Y', $timestamp))),
            'lastDayDate'  => date('Y-m-d', strtotime(date('t-m-Y', $timestamp))),
        ];
    }

    public function getWeekInfo($timestamp = null)
    {
        if (!$timestamp) {
            $timestamp = time();
        }
        $day = date('N', $timestamp);

        return [
            'dayNo' => $day,
            'dayName' => self::$daysOfWeek[$day],
            'dayDate' => date('Y-m-d', $timestamp),
            'week' => [
                1 => date('Y-m-d', $timestamp + ((1-$day) * self::DAY_IN_SECONDS)),
                3 => date('Y-m-d', $timestamp + (2-$day) * self::DAY_IN_SECONDS),
                4 => date('Y-m-d', $timestamp + (3-$day) * self::DAY_IN_SECONDS),
                5 => date('Y-m-d', $timestamp + (4-$day) * self::DAY_IN_SECONDS),
                6 => date('Y-m-d', $timestamp + (5-$day) * self::DAY_IN_SECONDS),
                2 => date('Y-m-d', $timestamp + (6-$day) * self::DAY_IN_SECONDS),
                7 => date('Y-m-d', $timestamp + (7-$day) * self::DAY_IN_SECONDS),
            ],
        ];
    }

    public function getNextWeek(int $weeks = 1): array
    {
        return [
            'timestamp' => strtotime('+' . $weeks . ' week'),
            'date'      => date('Y-m-d', strtotime('+' . $weeks . ' week')),
        ];
    }

    public function getLastWeek(int $weeks = 1): array
    {
        return [
            'timestamp' => strtotime('-' . $weeks . ' week'),
            'date'      => date('Y-m-d', strtotime('-' . $weeks . ' week')),
        ];
    }

    public function getNextMonth(int $months = 1): array
    {
        return [
            'timestamp' => strtotime('+' . $months . ' month'),
            'date'      => date('Y-m-d', strtotime('+' . $months . ' month')),
        ];
    }

    public function getLastMonth(int $months = 1): array
    {
        return [
            'timestamp' => strtotime('-' . $months . ' month'),
            'date'      => date('Y-m-d', strtotime('-' . $months . ' month')),
        ];
    }
}
