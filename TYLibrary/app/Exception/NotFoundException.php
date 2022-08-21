<?php namespace App\Exception;

class NotFoundException extends BaseException
{
    protected $status_code = 404;
    protected $default_message = "Not Found";
}
