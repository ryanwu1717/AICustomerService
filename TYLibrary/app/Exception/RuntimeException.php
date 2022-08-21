<?php namespace App\Exception;

class RuntimeException extends BaseException
{
    protected $status_code = 500;
    protected $default_message = "Server Error";
}
