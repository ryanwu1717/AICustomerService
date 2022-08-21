<?php namespace App\Factory;

use Dtkahl\SimpleConfig\Config;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;

class MonologFactory
{

    public function __invoke(Config $config)
    {
        $logger = new Logger($config->get("app.logger.name"));
        $logger->pushProcessor(new UidProcessor);
        $logger->pushHandler(new StreamHandler(
            $config->get("app.logger.path"),
            $config->get("app.logger.level", getenv("DEBUG") ? Logger::DEBUG : Logger::INFO)
        ));
        return $logger;
    }

}