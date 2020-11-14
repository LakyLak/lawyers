<?php


namespace app\models;


use app\core\db\DbModel;

class Appointment extends DbModel
{
    const STATUS_NEW      = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    public $lawyerId;
    public $citizenId;
    public $date;
    public $hour;
    public $status;

    public static $statuses = [
        self::STATUS_NEW      => 'New',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_REJECTED => 'Rejected',
    ];

    public function tableName(): string
    {
        return 'appointments';
    }

    public function attributes(): array
    {
        return ['lawyerId', 'citizenId', 'date', 'time', 'status'];
    }

    public function relations(): array
    {
        return [
            'lawyer'  => [self::HAS_ONE, Lawyer::class, 'lawyerId'],
            'citizen' => [self::HAS_ONE, Citizen::class, 'citizenId'],
        ];
    }

    public function rules(): array
    {
        return [
//            'law' => ['required'],
//            'lastName'  => ['required'],
//            'email'     => ['required', 'email'],
            //            @TODO implement scenario (on => insert) to existing rule
            //            'email'     => ['required', 'email', 'unique' => self::class],
        ];
    }

    public function getDisplayDateTime()
    {
        $hourFrom = $this->hour . ":00";
        $hourTo   = $this->hour + 1 . ":00";

        return "<b>$this->date</b> $hourFrom-$hourTo";
    }
}
