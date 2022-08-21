<?php namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
// use App\Factory;


class Reply {
 
    
    var $result;
    var $conn;
    function __construct(){
        // $db = $this->get('db');
        global $c;
        $this->conn = $c->get('db');
    }

    public function getLasttime(Request $request, Response $response, Twig $twig)
    {
        $sql ='SELECT "time", id
                FROM reply."lastTime"
                WHERE id= \'1\';';
        $sth = $this->conn->prepare($sql);
        $sth->execute();
        $row = $sth->fetchAll();
        return json_encode($row);
    }

    public function getTitle(Request $request, Response $response, Twig $twig)
    {
        $sql ='SELECT "id", "name"
                    FROM "reply"."title";';
        $sth = $this->conn->prepare($sql);
        // $sth->bindParam(':name',$name);
        $sth->execute();
        $row = $sth->fetchAll();

        // $row = $this->conn->lastInsertId();
       
      
        return json_encode($row);
        // return $_POST;
    }
    public function updateAppearCount(Request $request, Response $response, Twig $twig)
    {
        $updatedBody = ($request->getParsedBody());
        // return $updatedBody;
        $sql ='UPDATE reply."countAppear"
                SET read = true
                WHERE "titleID" = :titleID;';
        $sth = $this->conn->prepare($sql);
        $sth->bindParam(':titleID',$updatedBody['titleID']);
        $sth->execute();
        // $row = $sth->fetchAll();

        // $row = $this->conn->lastInsertId();
       
         $ack = array(
          'status' => 'success'
        );  
        return json_encode($ack);
        return $_POST;
    }

    

    public function getAppearCount(Request $request, Response $response, Twig $twig)
    {
        $sql ='SELECT  "countAppear"."titleID","title"."name",count(*)
                FROM reply."countAppear"
                LEFT JOIN(
                    SELECT "id", "name"
                    FROM "reply"."title"
                )AS "title" ON "title" ."id" = "countAppear"."titleID"
                WHERE  "countAppear"."read" = false  
                GROUP BY   "countAppear"."titleID","title"."name";';
        $sth = $this->conn->prepare($sql);
         // and  "countAppear"."time" >= current_timestamp - interval '59 minutes'
        // $tmptime = '59 minutes';
        // $sth->bindParam(':tmptime',$tmptime);
        $sth->execute();
        $row = $sth->fetchAll();

        // $row = $this->conn->lastInsertId();
       
        
        return json_encode($row);
        // return $_POST;
    }
    public function addAppear(Request $request, Response $response, Twig $twig)
    {
        $updatedBody = ($request->getParsedBody());

        $sql='';

        foreach ($updatedBody as $titlekey => $titlevalue) {
            // var_dump($titlevalue);

            $tmpArr = [];
            $lenValue = count($titlevalue);
            // var_dump($lenValue);

            if(($lenValue) == 0){
                continue;
            }
            $sql = 'select * from  reply."countAppear" where name  in (';
            // var_dump($titlevalue);

            for($i=0; $i<$lenValue;$i++){
                $tmpValue =  $titlevalue[$i];
                array_push($tmpArr,$tmpValue);
                $sql .= ':name';
                $sql .= $i;
                $sql .= ',';
            }
            $sql = substr($sql, 0, -1);
            $sql .= ')group by "titleID", "time", read, name;';
            $sth = $this->conn->prepare($sql);
            for($i=0; $i<$lenValue;$i++){
                // var_dump($titlevalue[$i]);

                $tmpBind = ':name'.$i;
                $tmpValue =  $titlevalue[$i];
                // var_dump($titlevalue[$i]);

                $sth->bindParam($tmpBind,  $titlevalue[$i]);
            }
            $sth->execute();  
            $row = $sth->fetchAll();

            // var_dump($sql);

            $diffArr = [];
            // var_dump($row);

            for($i=0; $i<count($row);$i++){
                $tmpName = $row[$i]['name'];
                array_push($diffArr,$tmpName);
            }



            $tmpArr = array_diff( $tmpArr,$diffArr);
            var_dump($tmpArr);
            // continue;
            // var_dump($tmpArr);
            //check finish
            
            $lenValue = count($tmpArr);
            if($lenValue == 0){
                continue;
            }

            $tmpCount=0;
            $sql = 'INSERT INTO "reply"."countAppear"("titleID", "name", "time","read") VALUES ';
           

            for($i=0; $i<$lenValue;$i++){
                // var_dump($titlevalue[$i]);
                // array_push($tmpArr,$titlevalue[$i]);
                $sql .= '(:titleID';
                $sql .= $i;
                $sql .= ', :url';
                $sql .= $i;
                $sql .= ', NOW(), false),';
            }
            $sql = substr($sql, 0, -1);
            $sth = $this->conn->prepare($sql);
            $bindCount=0;
            $tmpValue = '';
            foreach ($tmpArr as $appearkey => $appearvalue) {
                $tmpTitle = ':titleID'.$bindCount;
                // $tmpUrl = ':url'.$bindCount;
                
                $tmpTitleValue = intval($titlekey);
                // // var_dump($tmpTitle, $tmpTitleValue);
                $tmpValue = $appearvalue;
                $sth->bindParam($tmpTitle, $tmpTitleValue);
                // $sth->bindParam($tmpUrl, $tmpValue);

                $tmpUrl = ':url'.$bindCount;
                $sth->bindParam($tmpUrl, $tmpArr[ $appearkey ]);
                $bindCount+=1;
            }
            // for($i=0; $i<$lenValue;$i++){
            //     $tmpUrl = ':url'.$i;
            //     $sth->bindParam($tmpUrl, $tmpArr[$i]);

            // }
            var_dump($sql);
            $sth->execute();





        }
        $ack = array(
            'status'=>'success',
        );
        // return  $sql;
        return $ack;
        // return $_POST;
    }
    
}