<?php namespace App\Factory;

use Illuminate\Database\Capsule\Manager;
// use ..\Factory\DatabaseFactory.php;
class DatabaseFactory
{

    public function __invoke()
    {
        $capsule = new Manager;
        $capsule->addConnection([
            "driver" => getenv("DB_DRIVER"),
            "host" => getenv("DB_HOST"),
            "port" => getenv("DB_PORT"),
            "database" => getenv("DB_NAME"),
            "username" => getenv("DB_USER"),
            "password" => getenv("DB_PASS"),
            "charset" => getenv("DB_CHARSET") ?? "utf8",
            "collation" => getenv("DB_COLLATION") ?? "utf8_unicode_ci",
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        return $capsule->getConnection();
    }

}
