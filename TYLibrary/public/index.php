<?php
// phpinfo();
if (PHP_SAPI == "cli-server") {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER["REQUEST_URI"]);
    $file = __DIR__ . $url["path"];
    if (is_file($file)) {
        return false;
    }
}

// var_dump(session_status ( ) );

// if (session_id()) session_destroy();
    session_write_close();
    session_start();

const APP_ROOT = __DIR__ . "/..";

require APP_ROOT . "/vendor/autoload.php";
(new \Dotenv\Dotenv(APP_ROOT))->load();

// Instantiate the app
$app = new class() extends \DI\Bridge\Slim\App {
    protected function configureContainer(\DI\ContainerBuilder $builder)
    {
        $builder->addDefinitions([
            "settings.httpVersion" => "2.0",
            "settings.responseChunkSize" => 4096,
            "settings.outputBuffering" => "append",
            "settings.displayErrorDetails" => getenv("DEBUG"),
            "settings.determineRouteBeforeAppMiddleware" => true, // must be true for error handling etc.
            "settings.addContentLengthHeader" => false
        ]);
    }
};
/** @var \DI\Container $c */



$c = $app->getContainer();

$container['db'] = function ($container) {
    $dbhost = '127.0.0.1';
    // $dbport = '3306';
    $dbuser = 'root';
    $dbpasswd = '970314970314';
    $dbname = 'etest';
    $dsn = "mysql:host=".$dbhost.";dbname=".$dbname;
    try
    {

        $conn = new \PDO($dsn,$dbuser,$dbpasswd);
        $conn->exec("SET CHARACTER SET utf8");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected Successfully";
    }
    catch(PDOException $e)
    {
        echo "Connection failed: ".$e->getMessage();
    }
    return $conn;
};

require APP_ROOT . "/app/dependencies.php";
require APP_ROOT . "/app/routes.php";
require APP_ROOT . "/app/chatRoutes.php";
require APP_ROOT . "/app/middleware.php";

$app->run();
