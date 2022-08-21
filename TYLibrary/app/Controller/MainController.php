<?php namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;




class MainController
{
   
    public function getFirst(Request $request, Response $response, Twig $twig)
    {
        if (session_id()) session_destroy();
        session_start();
        return $twig->render($response, "first.twig");
    }
    public function getHome(Request $request, Response $response, Twig $twig)
    {
        phpinfo();
        return $twig->render($response, "home.twig");
    }
    public function getPttNews(Request $request, Response $response, Twig $twig)
    {
    	$url = "http://140.127.56.30:5984/ptt/news";
    	$req = curl_init($url);
    	curl_setopt($req, CURLOPT_HTTPHEADER, array(
		    'Authorization : Basic bmtudTowNzcxNzI5MzA='
		));
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($req, true);
        
		$result = curl_exec($req);   
        /*
        $oldBody = json_decode($result,true); 
        $revision = $oldBody['_rev'];
        $data = $oldBody['data'];
        // $data = $data[0];
        // var_dump($data);
        $ack = array('data'=> $data );
        // var_dump($ack);
        */
        return $result;
    }
    public function updatePttNews(Request $request, Response $response, Twig $twig)
    {
        $updatedBody = ($request->getParsedBody());

        $url = "http://140.127.56.30:5984/ptt/news";
        $req = curl_init($url);
        curl_setopt($req, CURLOPT_HTTPHEADER, array(
            'Authorization : Basic bmtudTowNzcxNzI5MzA='
        ));
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($req);   
        
        $oldBody = json_decode($result,true); 
        // var_dump($oldBody["15"]["上"]);
        
        $revision = $oldBody['_rev'];
        foreach ($updatedBody as $mainPhraseKey => $mainPhraseValues) {
            if($mainPhraseKey != "_rev" && $mainPhraseKey != "_id"){
                if(array_key_exists($mainPhraseKey, $oldBody)){
                    foreach ($mainPhraseValues as $assocPhraseKey => $assocPhraseValue) {
                        if(array_key_exists($assocPhraseKey, $oldBody[$mainPhraseKey])){
                            $oldBody[$mainPhraseKey][$assocPhraseKey] += $assocPhraseValue;
                        }
                        else{
                            $oldBody[$mainPhraseKey][$assocPhraseKey] =  $assocPhraseValue;
                        }
                    }
                }
                else{
                    $oldBody[$mainPhraseKey] = $mainPhraseValues;
                }
            }
        }
        // var_dump($oldBody["15"]["上"]);
        curl_close($req);
        #var_dump($data);

        $req2 = curl_init($url);
        curl_setopt($req2, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($req2, CURLOPT_RETURNTRANSFER, true);
        $datastring = json_encode($oldBody);
        curl_setopt($req2, CURLOPT_POSTFIELDS, $datastring);
        curl_setopt($req2, CURLOPT_HTTPHEADER, array('Authorization : Basic bmtudTowNzcxNzI5MzA=','Content-Type: application/json','Content-Length: ' . strlen($datastring)));
        /*
        curl_setopt($req2, CURLINFO_HEADER_OUT, true);
        $headerSent = curl_getinfo($req2, CURLINFO_HEADER_OUT );
        echo($headerSent);*/
        $result = curl_exec($req2);
        curl_close($req2);
        // var_dump($result);
        return  $updatedBody;
    } 
    public function getLineUpdate(Request $request, Response $response, Twig $twig)
    {
        $url = "http://140.127.56.30:5984/line/lastUpdated";
        $req = curl_init($url);
        curl_setopt($req, CURLOPT_HTTPHEADER, array(
            'Authorization : Basic bmtudTowNzcxNzI5MzA='
        ));
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($req);   
        $result = json_decode($result,true);
        $result = $result['updates'];
        $result = json_encode($result);
        return $result;
    }
    public function updateLineUpdate(Request $request, Response $response, Twig $twig)
    {
        $newUpdate = ($request->getParsedBody());
        $currTime = date('Y-m-d:H');
        $currDay = date('Y-m-d');

        $url = "http://140.127.56.30:5984/line/lastUpdated";
        $req = curl_init($url);
        curl_setopt($req, CURLOPT_HTTPHEADER, array(
            'Authorization : Basic bmtudTowNzcxNzI5MzA='
        ));
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($req);   
        $prevUpdate = json_decode($result,true);


        foreach ($prevUpdate['updates'] as $id => $time) {
            $oldTime = substr($time, 0, strpos($time, ":"));
            
            if($currDay != $oldTime){
                unset($prevUpdate['updates'][$id]);
            }
            
        }
        if(is_array($newUpdate)){
            foreach ($newUpdate as $count => $id) {
                if(array_key_exists($id, $prevUpdate['updates'])){
                    #Do nothing currently
                }
                else{
                    $prevUpdate['updates'][$id] = $currTime;
                }
            }
        }
        curl_close($req);

        $req2 = curl_init($url);
        curl_setopt($req2, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($req2, CURLOPT_RETURNTRANSFER, true);
        $datastring = json_encode($prevUpdate);
        curl_setopt($req2, CURLOPT_POSTFIELDS, $datastring);
        curl_setopt($req2, CURLOPT_HTTPHEADER, array('Authorization : Basic bmtudTowNzcxNzI5MzA=','Content-Type: application/json','Content-Length: ' . strlen($datastring)));

        $result = curl_exec($req2);
        curl_close($req2);

        var_dump($prevUpdate);
        return $prevUpdate;
    }
    public function getChatData ($request, Response $response, Twig $twig)
    {
        $url = "http://140.127.56.30:5984/chatcontent/content";
        $req = curl_init($url);
        curl_setopt($req, CURLOPT_HTTPHEADER, array(
            'Authorization : Basic bmtudTowNzcxNzI5MzA='
        ));
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($req, true);
        
        $result = curl_exec($req); 
        return $result;  
    }
    public function updateChatData(Request $request, Response $response, Twig $twig)
    {
        $updatedBody = ($request->getParsedBody());

        $url = "http://140.127.56.30:5984/chatcontent/content";
        $req = curl_init($url);
        curl_setopt($req, CURLOPT_HTTPHEADER, array(
            'Authorization : Basic bmtudTowNzcxNzI5MzA='
        ));
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($req);   
        
        $oldBody = json_decode($result,true); 
        // var_dump($oldBody["15"]["上"]);
        
        $revision = $oldBody['_rev'];
        foreach ($updatedBody as $id => $phrases) {
            if($id != "_rev" && $id != "_id"){
                if(array_key_exists($id, $oldBody)){
                    foreach ($phrases as $singlePhrase => $phraseCount) {
                        if(array_key_exists($singlePhrase, $oldBody[$id])){
                            $oldBody[$id][$singlePhrase] += $phraseCount;
                        }
                        else{
                            $oldBody[$id][$singlePhrase] =  $phraseCount;
                        }
                    }
                }
                else{
                    $oldBody[$id] = $phrases;
                }
            }
        }
        // var_dump($oldBody["15"]["上"]);
        curl_close($req);
        #var_dump($data);

        $req2 = curl_init($url);
        curl_setopt($req2, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($req2, CURLOPT_RETURNTRANSFER, true);
        $datastring = json_encode($oldBody);
        curl_setopt($req2, CURLOPT_POSTFIELDS, $datastring);
        curl_setopt($req2, CURLOPT_HTTPHEADER, array('Authorization : Basic bmtudTowNzcxNzI5MzA=','Content-Type: application/json','Content-Length: ' . strlen($datastring)));
        /*
        curl_setopt($req2, CURLINFO_HEADER_OUT, true);
        $headerSent = curl_getinfo($req2, CURLINFO_HEADER_OUT );
        echo($headerSent);*/
        $result = curl_exec($req2);
        curl_close($req2);
        // var_dump($result);
        return  $updatedBody;
    }

}
