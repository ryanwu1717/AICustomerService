<?php namespace App\Middleware;

use App\Exception\BaseException;
use App\Exception\MaintenanceException;
use App\Exception\NotFoundException;
use App\Exception\RuntimeException;
use DI\Container;
use Dtkahl\SimpleConfig\Config;
use Monolog\Logger;
use Slim\Handlers\Error;
use Slim\Handlers\PhpError;
use Slim\Http\Request;
use Slim\Http\Response;

class ExceptionHandlingMiddleware
{

    private $config;
    private $logger;
    private $container;

    public function __construct(Container $container, Config $config, Logger $logger)
    {
        $this->container = $container;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        try {
            if ($this->isMaintenance()) {
                throw new MaintenanceException;
            }
            if (is_null($request->getAttribute("route"))) {
                throw new NotFoundException;
            }
            $response = $next($request, $response);
        } catch (\Exception $e) {
            $response = $this->handleException($request, $response, $e);
        } catch (\Throwable $e) {
            $response = $this->handleException($request, $response, $e);
        }
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param \Exception|\Throwable $exception
     * @return Response
     */
    public function handleException(Request $request, Response $response, $exception) {
        $this->logger->error("Exception", ["exception" => $exception]);

        $original_exception = $exception;
        if ($exception instanceof \Slim\Exception\NotFoundException) {
            $exception = new NotFoundException($exception->getMessage(), $exception->getCode(), $exception);
        } elseif (!$exception instanceof BaseException) {
            $exception = new RuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }

        if (getenv("DEBUG") && !$original_exception instanceof BaseException) {
            $exception = $original_exception instanceof \Exception ? new Error(true) : new PhpError(true);
            /** @var Response $response */
            $response = $exception($request, $response, $original_exception);
            return $response;
        }

        return $exception->render($request, $response, $this->container);
    }

    public function isMaintenance()
    {
       return file_exists(APP_ROOT . "/storage/down");
    }

}
