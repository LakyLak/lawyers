<?php


namespace app\core\exceptions;


class ApiException extends \Exception
{
    protected $code = 404;
}
