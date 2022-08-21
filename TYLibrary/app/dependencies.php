<?php
/**
 * @var \DI\Container $c
 */

$c->set(\Dtkahl\SimpleConfig\Config::class, DI\factory(new \App\Factory\ConfigFactory));
$c->set(\Monolog\Logger::class, DI\factory(new \App\Factory\MonologFactory));
$c->set(\Illuminate\Database\Connection::class, DI\factory(new \App\Factory\DatabaseFactory));
$c->set(\Slim\Views\Twig::class, DI\factory(new \App\Factory\TwigFactory));
$c->set(\Dtkahl\FileCache\Cache::class, DI\factory(new \App\Factory\CacheFactory));
$c->set('db', function () {
    $dbhost = '140.127.49.168';
	$dbport = '5432';
	$dbuser = 'minlab';
	$dbpasswd = '970314970314';
	$dbname = 'humanresourceclone';
	$dsn = "pgsql:host=".$dbhost.";port=".$dbport.";dbname=".$dbname;
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
});
