<?php namespace App\Factory;

use Dtkahl\FileCache\Cache;
use Dtkahl\SimpleConfig\Config;

class CacheFactory
{

    public function __invoke(Config $config)
    {
        return new Cache($config->get("app.cache.path"));
    }

}