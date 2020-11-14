<?php


namespace app\core;


class Session
{
    const FLASH_KEY = 'flash_messages';
    const USER_KEY  = 'web_user';

    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        foreach ($flashMessages as &$flashMessage) {
            $flashMessage['remove'] = true;
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        foreach ($flashMessages as $key => $flashMessage) {
            if ($flashMessage['remove']) {
                unset($_SESSION[self::FLASH_KEY][$key]);
            }
        }
    }

    public function setFlashMessage(string $key, string $message): void
    {
        $_SESSION[self::FLASH_KEY][$key] =
            [
                'remove' => false,
                'value'  => $message,
            ];
    }

    public function getFlashMessage(string $key): ?string
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key): ?string
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }
}
