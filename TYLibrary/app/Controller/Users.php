<?php namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
// use App\Factory;


class Users {
 
    
    var $result;
    var $conn;
    function __construct(){
        // $db = $this->get('db');
        global $c;
        $this->conn = $c->get('db');
    }

    public function addNewUser(Request $request, Response $response, Twig $twig)
    {
        $data = ($request->getParsedBody());
        $name = $data['name'];
        // $data=json_decode( $data,true);


        $sql ='INSERT INTO staff."user"("name")
                VALUES (:name);';
        $sth = $this->conn->prepare($sql);
        $sth->bindParam(':name',$name);
        $sth->execute();
        $row = $this->conn->lastInsertId();
        $ack = array(
            'status'=>'success',
            'name'=>$name,
            'id'=>$row
        );
        // $_SESSION['id'] = $row;
        // // $row = $sth->fetchAll();
        // // return $row;
        return json_encode($ack);
        // return $_POST;
    }
     public function setSession(Request $request, Response $response, Twig $twig, $UID)
    {
        
        $sql ='SELECT*
                FROM  staff."user"
                WHERE id = :id;';
        $sth = $this->conn->prepare($sql);
        $sth->bindParam(':id',$UID);
        $sth->execute();
        $row = $sth->fetchAll();
        if(count($row) == 1){
            $ack = array(
                'status'=>'success',
                // 'content'=>$row
            );
            $_SESSION['id'] = $row[0]['id'];
        }else{
             $ack = array(
                'status'=>'failed',
                // 'content'=>$row
            );
        }
        // session_destroy();
        // session_start();
        $_SESSION['id'] = 'L003';
        return json_encode($ack);

    }

}