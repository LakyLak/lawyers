<?php


namespace app\models;


use app\core\Application;
use app\core\Model;

class LoginForm extends Model
{
    public $email;
    public $password;

    public $webUser;

    /**
     * LoginForm constructor.
     *
     * @param $webUser
     */
    public function __construct($webUser)
    {
        $this->webUser = $webUser;
    }


    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function login()
    {
        $className = get_class($this->webUser);

        $user = $className::findByAttributes(['email' => $this->email]);

        if (!$user) {
            $this->addError('email', 'The user with this email already exists.');
            return false;
        }

        if (!password_verify($this->password, $user->password)) {
            $this->addError('password', 'The password is incorrect');
            return false;
        }

        return Application::$app->login($user);

    }
}
