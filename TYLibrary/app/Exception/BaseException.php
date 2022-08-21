<?php namespace App\Exception;

use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

abstract class BaseException extends \Exception
{
    protected $view = "error.twig";
    protected $status_code = 500;
    protected $default_message;

    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|\Exception|null $previous
     */
    public function __construct($message = "", $code = 0, $previous = null)
    {
        if (empty($message) && !empty($this->default_message)) {
            $message = $this->default_message;
        }
        parent::__construct($message, $code, $previous);
    }

    public function getParams()
    {
        return ["code" => $this->code, "message" => $this->message ?: get_class($this)];
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param ContainerInterface $container
     * @return Response
     */
    public function render(Request $request, Response $response, ContainerInterface $container)
    {
        $response = $response->withStatus($this->status_code);
        if (strpos($request->getHeaderLine("Accept"), "application/json") !== false) {
            return $response->withJson($this->getParams());
        }
        return $container->get(Twig::class)->render($response, $this->view, $this->getParams());
    }

}
