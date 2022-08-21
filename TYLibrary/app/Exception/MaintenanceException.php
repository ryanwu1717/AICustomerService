<?php namespace App\Exception;

class MaintenanceException extends BaseException
{
    protected $status_code = 503;
    protected $default_message = "Maintenance";
}
