<?php


namespace app\core;


use http\Header;

class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function redirect($url)
    {
        header('Location: ' . $url);
    }
}
