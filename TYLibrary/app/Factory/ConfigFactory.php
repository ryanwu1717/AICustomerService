<?php namespace App\Factory;

use Dtkahl\SimpleConfig\Config;

class ConfigFactory
{

    public function __invoke()
    {
        $files = array_diff(scandir(APP_ROOT . "/config/"), ["..", "."]);
        $raw = [];
        foreach ($files as $file) {
            $raw[pathinfo($file, PATHINFO_FILENAME)] = require APP_ROOT . "/config/" . $file;
        }
        return new Config($raw);
    }

}