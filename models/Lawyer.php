<?php


namespace app\models;


use app\core\db\DbModel;

class Lawyer extends DbModel
{
    public $firstName = '';
    public $lastName  = '';
    public $email     = '';
    public $password  = '';
    public $confirmPassword = '';

    public function tableName(): string
    {
        return 'lawyers';
    }

    public function attributes(): array
    {
        return ['firstName', 'lastName', 'email', 'password'];
    }

    public function relations(): array
    {
        return [
//            'customerPlan' => [self::BELONGS_TO_ONE, CustomerPlan::class, 'customerId'],
        ];
    }

    public function save()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        return parent::save();
    }

    public function rules(): array
    {
        return [
            'firstName'       => ['required'],
            'lastName'        => ['required'],
            'email'           => ['required', 'email', 'unique' => self::class],
            'password'        => ['required', 'min' => 3, 'max' => 6],
            'confirmPassword' => ['required', 'match' => 'password'],
        ];
    }

    public function getDisplayName()
    {
        return "$this->firstName $this->lastName";
    }
}
