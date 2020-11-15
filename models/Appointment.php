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
        return ['lawyerId', 'citizenId', 'date', 'hour', 'status'];
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
            'lawyerId'  => ['required'],
            'citizenId' => ['required'],
        ];
    }

    public function save()
    {
        $this->status = self::STATUS_NEW;

        return parent::save();
    }

    public function getDisplayDateTime()
    {
        $hourFrom = $this->hour . ":00";
        $hourTo   = $this->hour + 1 . ":00";

        return "<b>$this->date</b> $hourFrom-$hourTo";
    }

    public static function getHumanHour(int $hour): array
    {
        return [
            'start' => $hour . ":00",
            'end'   => $hour + 1 . ":00",
        ];
    }
}
