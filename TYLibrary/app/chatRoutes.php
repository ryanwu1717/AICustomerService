<?php

require_once  APP_ROOT . "/app/Controller/chat.php";

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;


$app->group('/checkSession', function () use ($app) {
	$app->get('/{timestamp}', function (Request $request, Response $response) {
		// session_start();	
		$route = $request->getAttribute('route');
		// echo($route);
    	$tmp = $route->getArgument('timestamp');	
    	$args['timestamp'] = $tmp;	
		var_dump($_SESSION['last'][$args['timestamp']]);
	});
	$app->get('', function (Request $request, Response $response) {
		// session_start();	
		// phpinfo();
		var_dump($_SESSION);
	});
});

$app->group('/staff', function () use ($app) {
	$app->get('/name/{id}/{type}', function (Request $request, Response $response) {		

		$route = $request->getAttribute('route');
    	$args = $route->getArguments();	

	    $staff = new Staff();
	    $result = $staff->getStaffName($args['id'],$args['type']);	    
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/name/{id}/{type}/{chatID}', function (Request $request, Response $response) {	
		$route = $request->getAttribute('route');
    	$args = $route->getArguments();	

	    $staff = new Staff();
	    $result = $staff->getStaffName($args['id'],$args['type'],$args['chatID']);	    

	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;	    
	});
	$app->get('/department/{id}', function (Request $request, Response $response) {
		$route = $request->getAttribute('route');
    	$tmp = $route->getArgument('id');	
    	$args['id'] = $tmp;		
	    $staff = new Staff();

	    $result = $staff->getDepartment($args['id']);	    
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});

	
});
$app->group('/table', function () use ($app) {
	
});

$app->group('/chat', function () use ($app) {
	$app->get('/init/{timestamp}', function (Request $request, Response $response) {

		$route = $request->getAttribute('route');
    	$tmp = $route->getArgument('timestamp');	
    	$args['timestamp'] = $tmp;

		$chat = new Chat();
		$result = $chat->init();
	    session_start(); 
		// session_start();
		$_SESSION['last'][$args['timestamp']] = $result;
		// var_dump(expression)
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	

	$app->get('/routine/{timestamp}/{chatID}/{limit}', function (Request $request, Response $response) {
		$route = $request->getAttribute('route');
    	$args = $route->getArguments();

		$data = $_SESSION['last'][$args['timestamp']];
		$_SESSION['chatID'][$args['timestamp']] = $args['chatID'];
		// var_dump('ddd');
		// var_dump($args['timestamp']);
		// var_dump($data);
		// var_dump($_SESSION['last'][$args['timestamp']]);
		if(!isset($_SESSION['now'])){
			$_SESSION['now'] = 0;
		}else{
			$_SESSION['now'] = $_SESSION['now']+1;
			if($_SESSION['now']==65535)
				$_SESSION['now'] = 0;
		}
		if($args['chatID']==$data['result']['chat']['chatID'] && $data['limit'] != $args['limit']){
			// echo "string";
			$result = $data;
			$_SESSION['last'][$args['timestamp']]['limit'] = $args['limit'];
			session_write_close();
			$chat = array();
			for($i = $args['limit'];$i<count($result['chat']);$i++){
				array_push($chat, $result['chat'][$i]);
			}
			$result['chat'] = $chat;
			if($args['limit']-10<0){
				$result['limit'] = 0;
			}else{
				$result['limit'] = $args['limit'];
			}	
		}else{
			$chat = new Chat();
			$result = $chat->routine($data,$args['chatID'],$args['timestamp']);		
			if($args['limit']==-1){
				if(count($result['chat'])-10<0){
					$result['limit'] = 0;
				}else{
					$result['limit'] = count($result['chat'])-10;
				}	
			}else{
				$result['limit'] = $args['limit'];
			}
			$chat = array();
			for($i = $result['limit'];$i<count($result['chat']);$i++){
				array_push($chat, $result['chat'][$i]);
			}
			session_start();
			$_SESSION['last'][$args['timestamp']] = $result;
			$_SESSION['chat'][$_SESSION['id']][$args['chatID']] = $result;
			$result['chat'] = $chat;
		}
    	$response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
		// var_dump($_SESSION['last'][$args['timestamp']]);
	    
	    return $response;
	});
	$app->get('/aicontent/{time}', function (Request $request, Response $response) {
		$route = $request->getAttribute('route');
    	$tmp = $route->getArgument('time');	
    	$args['time'] = $tmp;

		$chat = new Chat();
		$result = $chat->getContent($args['time']);

	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/content/{chatID}/{UID}', function (Request $request, Response $response) {
		$route = $request->getAttribute('route');
    	$args = $route->getArguments();
		$chat = new Chat();
		$result = $chat->getChat($args['chatID'],$args['UID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->group('/star', function () use ($app) {
		$app->get('', function(Request $request, Response $response){
			$star = new Chat();
			$result = $star->getStar();
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->post('', function(Request $request, Response $response){
			$star = new Chat();
			$result = $star->addStar();
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->delete('', function(Request $request, Response $response, array $args){
			$star = new Chat();
			$result = $star->deleteStar();
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
	});
	$app->get('/lastOnLine/{UID}', function (Request $request, Response $response) {
		$route = $request->getAttribute('route');
		// echo($route);
    	$tmp = $route->getArgument('UID');	
    	$args['UID'] = $tmp;

		$chat = new Chat();
		$result = $chat->getLastOnLine($args['UID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->group('/chatroom', function () use ($app) {
		$app->get('', function (Request $request, Response $response) {
			$chat = new Chat();
			$result = $chat->getChatroom($_GET);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->get('/{UID}', function (Request $request, Response $response) {
			$route = $request->getAttribute('route');
	    	$tmp = $route->getArgument('UID');	
	    	$args['UID'] = $tmp;
			$chat = new Chat();
			$result = $chat->getChatHistory($args['UID']);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->post('', function (Request $request, Response $response) {
			$chat = new Chat();
			$result = $chat->createChatroom($request->getParsedBody());
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->patch('', function (Request $request, Response $response) {
			$chat = new Chat();
			$result = $chat->updateChatroom($request->getParsedBody());
			$result = array("status"=>"success");
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->delete('', function (Request $request, Response $response) {
			$chat = new Chat();
			$result = $chat->deleteChatroom($request->getParsedBody());
			$result = array("status"=>"success");
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->get('/title/{chatID}', function (Request $request, Response $response) {
			$route = $request->getAttribute('route');
	    	$tmp = $route->getArgument('chatID');	
	    	$args['chatID'] = $tmp;
			$chat = new Chat();
			$result = $chat->getChatroomTitle($args['chatID']);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
	});

	$app->get('/commentID/{chatID}/{sendtime}', function (Request $request, Response $response) {//TODO, borrow readlist for testing
		$route = $request->getAttribute('route');
    	$args = $route->getArguments();
    	
		$chat = new Chat();
		$result = $chat->getCommentID($args['chatID'],$args['sendtime']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->patch('/message', function (Request $request, Response $response) {
		$chat = new Chat();
		$result = $chat->updateMessage($request->getParsedBody());
		// var_dump($result);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->patch('/message/ai', function (Request $request, Response $response) {
		$chat = new Chat();
		$result = $chat->aiSendMessage($request->getParsedBody());
		// var_dump($result);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->group('/report', function () use ($app) {
		$app->patch('', function(Request $request, Response $response){
			$chat = new Chat();
			$result = $chat->updateReport($request->getParsedBody());
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
	});
	$app->post('/delete',function (Request $request, Response $response){
		$chat = new Chat();
		$result = $chat->addDelete();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->patch('/heart',function (Request $request, Response $response){
		$chat = new Chat();
		$result = $chat->patchHeart();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->patch('/lastReadTime', function (Request $request, Response $response) {
		$chat = new Chat();
		$result = $chat->updateLastReadTime($request->getParsedBody());
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->group('/notification', function () use ($app) {
		$app->get('/', function(Request $request, Response $response){
			$notification = new Chat();
			$result = $notification->getNotification();
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->patch('/{id}', function(Request $request, Response $response){
			
			$route = $request->getAttribute('route');
			// echo($route);
	    	$tmp = $route->getArgument('id');	
	    	$args['id'] = $tmp;

			$notification = new Chat();
			$result = $notification->readNotification($args['id']);
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->post('/tag', function (Request $request, Response $response) {
		    $notification = new Chat();
		    $result = $notification->tag();
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;

		});
		$app->post('/comment', function (Request $request, Response $response) {
		    $notification = new Chat();
		    $result = $notification->commentTag();
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;

		});
		
	});
	$app->get('/content/{chatID}', function (Request $request, Response $response) {
		$route = $request->getAttribute('route');
		// echo($route);
    	$tmp = $route->getArgument('chatID');	
    	$args['chatID'] = $tmp;

		$chat = new Chat();
		$result = $chat->getChatContent($args['chatID'],$_GET);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/member/{chatID}', function (Request $request, Response $response) {
		$route = $request->getAttribute('route');
		// echo($route);
    	$tmp = $route->getArgument('chatID');	
    	$args['chatID'] = $tmp;

		$chat = new Chat();
		$result = $chat->getMember($args['chatID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/department/{chatID}', function (Request $request, Response $response) {
		$route = $request->getAttribute('route');
		// echo($route);
    	$tmp = $route->getArgument('chatID');	
    	$args['chatID'] = $tmp;
		$chat = new Chat();
		$result = $chat->getMemberDepartment($args['chatID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->group('/file', function () use ($app) {
		$app->post('/{chatID}', function (Request $request, Response $response) {
			$route = $request->getAttribute('route');
			// echo($route);
	    	$tmp = $route->getArgument('chatID');	
	    	$args['chatID'] = $tmp;
			$chat = new Chat();
			$result = $chat->uploadFile($args['chatID'],$this->upload_directory,$request->getUploadedFiles(),false);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});

		$app->get('/{fileID}', function (Request $request, Response $response) {
			$route = $request->getAttribute('route');
			// echo($route);
	    	$tmp = $route->getArgument('chatID');	
	    	$args['chatID'] = $tmp;
			$chat = new Chat();
			$result = $chat->downloadFile($args['fileID']);
			if(isset($result['data'])){
		    	$file = $this->upload_directory.'/'.$result['data']['fileName'];
			    $response = $response
			    	->withHeader('Content-Description', 'File Transfer')
				   	->withHeader('Content-Type', 'application/octet-stream')
				   	->withHeader('Content-Disposition', 'attachment;filename="'.$result['data']['fileNameClient'].'"')
				   	->withHeader('Expires', '0')
				   	->withHeader('Cache-Control', 'must-revalidate')
				   	->withHeader('Pragma', 'public')
				   	->withHeader('Content-Length', filesize($file));
				readfile($file);
			}else{
			    $response = $response->withHeader('Content-type', 'application/json' );
				$response = $response->withJson($result);
			}
			return $response;
		});	
	});

	$app->group('/picture', function () use ($app) {
		$app->post('/{chatID}', function (Request $request, Response $response) {
			$route = $request->getAttribute('route');
			// echo($route);
	    	$tmp = $route->getArgument('chatID');	
	    	$args['chatID'] = $tmp;
			$chat = new Chat();
			$result = $chat->uploadFile($args['chatID'],$this->upload_directory,$request->getUploadedFiles(),true);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->get('/{fileID}', function (Request $request, Response $response) {
			$route = $request->getAttribute('route');
			// echo($route);
	    	$tmp = $route->getArgument('fileID');	
	    	$args['fileID'] = $tmp;
			$chat = new Chat();
			$result = $chat->downloadFile($args['fileID']);
			if(isset($result['data'])){
		    	$file = $this->upload_directory.'/'.$result['data']['fileName'];
	    	    $image = @file_get_contents($file);
	    		$response->write($image);
			    return $response->withHeader('Content-Type', FILEINFO_MIME_TYPE)
				   	->withHeader('Content-Disposition', 'inline;filename="'.$result['data']['fileNameClient'].'"');
			}else{
			    $response = $response->withHeader('Content-type', 'application/json' );
				$response = $response->withJson($result);
			}
			return $response;
		});
	});
	$app->group('/comment', function () use ($app) {
		$app->get('/{commentID}', function (Request $request, Response $response) {//TODO, borrow readlist for testing
			$route = $request->getAttribute('route');
	    	$tmp = $route->getArgument('commentID');	
	    	$args['commentID'] = $tmp;
			$chat = new Chat();
			$result = $chat->getComment($args['commentID']);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->post('/{commentID}', function (Request $request, Response $response) { //TODO
			$route = $request->getAttribute('route');
	    	$tmp = $route->getArgument('commentID');	
	    	$args['commentID'] = $tmp;
			$content = $request->getParsedBody()['Msg'];
			$chat = new Chat();
			$result = $chat->insertComment($args['commentID'],$content);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->get('/member/{commentID}/{orgSender}', function (Request $request, Response $response) { //TODO
			$route = $request->getAttribute('route');
    		$args = $route->getArguments();
			$chat = new Chat();
			$result = $chat->getCommentMember($args['commentID'],$args['orgSender']);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->get('/senter/{commentID}', function (Request $request, Response $response) { //TODO
			$route = $request->getAttribute('route');
	    	$tmp = $route->getArgument('commentID');	
	    	$args['commentID'] = $tmp;
			$chat = new Chat();
			$result = $chat->getSenter($args['commentID']);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
	});
	$app->patch('/commentReadTime/{commentID}', function (Request $request, Response $response) {
		$route = $request->getAttribute('route');
    	$tmp = $route->getArgument('commentID');	
    	$args['commentID'] = $tmp;
		$chat = new Chat();
		$result = $chat->updateCommentReadTime($args['commentID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/commentReadlist/{commentID}/{senttime}/{UID}/{chatID}', function (Request $request, Response $response) {//TODO, borrow readlist for testing
		$route = $request->getAttribute('route');
    	$args = $route->getArguments();
		$chat = new Chat();
		$result = $chat->getCommentReadList($args['commentID'],$args['senttime'],$args['UID'],$args['chatID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->group('/class', function () use ($app) {
		$app->get('/', function (Request $request, Response $response) {
		    $class = new Chat();
		    $result = $class->getClass();
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;

		});
		$app->get('/{classId}/', function (Request $request, Response $response) {
			$route = $request->getAttribute('route');
    		$tmp = $route->getArgument('classId');	
    		$args['classId'] = $tmp;

		    $class = new Chat();
		    $result = $class->getClass($args['classId']);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;

		});
		$app->delete('/{classId}/', function (Request $request, Response $response) {
			$route = $request->getAttribute('route');
    		$tmp = $route->getArgument('classId');	
    		$args['classId'] = $tmp;

		    $class = new Chat();
		    $result = $class->deleteClass($args['classId']);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;

		});
		$app->patch('/{classId}/{chatID}/', function (Request $request, Response $response) {
			$route = $request->getAttribute('route');
    		$args = $route->getArguments();

		    $class = new Chat();
		    $result = $class->insertClass($args['classId'],$args['chatID']);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;

		});
		$app->post('/', function (Request $request, Response $response) {
		    $class = new Chat();
		    $result = $class->addClass();
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;

		});
	});
	$app->get('/readcount', function (Request $request, Response $response) {
		$chat = new Chat();
		$result = $chat->getReadCount($_GET);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/readlist', function (Request $request, Response $response) {
		$chat = new Chat();
		$result = $chat->getReadList($_GET);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/list', function (Request $request, Response $response) {
		$chat = new Chat();
		$result = $chat->getList();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->group('/AI', function () use ($app) {
		$app->get('/check', function (Request $request, Response $response) {
		$chat = new Chat();
		$result = $chat->checkAI();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	});




});

$app->group('/workTime', function () use ($app) {
	
});

$app->group('/work', function () use ($app) {
	


});

?>