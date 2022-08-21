<?php namespace App\PDOFactory;

// use Illuminate\Database\Capsule\Manager;
// use ..\Factory\DatabaseFactory.php;
class PDOFactory
{

    public function __invoke()
    {
        // $dbhost = '127.0.0.1';
        // // $dbport = '3306';
        // $dbuser = 'root';
        // $dbpasswd = '970314970314';
        // $dbname = 'etest';
        // $dsn = "mysql:host=".$dbhost.";dbname=".$dbname;
        // try
        // {

        //     $conn = new \PDO($dsn,$dbuser,$dbpasswd);
        //     $conn->exec("SET CHARACTER SET utf8");
        //     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //     //echo "Connected Successfully";
        // }
        // catch(PDOException $e)
        // {
        //     echo "Connection failed: ".$e->getMessage();
        // }
        // return $conn;
    }

}
