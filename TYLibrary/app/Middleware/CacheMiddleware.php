<?php namespace App\Middleware;

use Dtkahl\FileCache\Cache;
use Slim\Http\Request;
use Slim\Http\Response;

class CacheMiddleware
{
    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $response = $next($request, $response);
        $this->cache->writeCache();
        return $response;
    }

}