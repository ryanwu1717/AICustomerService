<?php
require __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../model/user.php';
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;

//$app = new \Slim\App;
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    return new PhpRenderer(__DIR__.'/../view');
};
$container['db'] = function ($container) {
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
};
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('Page not found');
    };
};
$container['ViewMiddleware'] = function($container) {
    return new ViewMiddleware($container->db);
};
$container['APIMiddleware'] = function($container) {
    return new APIMiddleware($container->db);
};
$container['upload_directory'] = __DIR__ . '/../uploads';
session_start();
require_once __DIR__.'/../route/management.php';


class ViewMiddleware
{
	private $conn;
	function __construct($db){
		$this->conn = $db;
	}
    public function __invoke($request, $response, $next)
    {
    	if(isset($_SESSION['id'])){
    		$user = new User($this->conn);
	    	$name = $user->getName();
    		$viewParam = array();
	    	if(count($name)==1){
	    		$viewParam['name'] = $name[0]['staff_name'];
	    	}
    		$request = $request->withAttribute('viewParam', $viewParam);
        	$response = $next($request, $response);
    	}
    	else{
			return $response->withRedirect('/login', 301);
    	}
        return $response;
    }
}
class APIMiddleware
{
	private $conn;
	function __construct($db){
		$this->conn = $db;
	}
    public function __invoke($request, $response, $next)
    {
    	$response = $next($request, $response);

		$this->conn = null;
        return $response;
    }
}
$app->group('', function () use ($app) {
	$app->group('', function () use ($app) {
		$app->get('/', function (Request $request, Response $response, array $args) {		
			return $response->withRedirect('/home', 301);
		});
		$app->get('/home', function (Request $request, Response $response, array $args) {	
			$viewParam = $request->getAttribute('viewParam');	
			return $this->view->render($response, '/index.php', $viewParam);
		});
                $app->get('/test', function (Request $request, Response $response, array $args) {
                        $viewParam = $request->getAttribute('viewParam');
                        return $this->view->render($response, '/test.php', $viewParam);
                });
		// $app->get('/register', function (Request $request, Response $response, array $args) {	
		// 	$viewParam = $request->getAttribute('viewParam');		
		// 	return $this->view->render($response, '/register.php', $viewParam);
		// });
		// $app->get('/table', function (Request $request, Response $response, array $args) {	
		// 	$viewParam = $request->getAttribute('viewParam');		
		// 	return $this->view->render($response, '/tables.php', $viewParam);
		// });
		// $app->get('/modify', function (Request $request, Response $response, array $args) {	
		// 	$viewParam = $request->getAttribute('viewParam');		
		// 	return $this->view->render($response, '/register.php', $viewParam);
		// });
	})->add('ViewMiddleware');
	$app->get('/login', function (Request $request, Response $response, array $args) {	
		session_destroy();
		session_start();
		return $this->view->render($response, '/login.php', []);
	});
})->add('APIMiddleware');

$app->group('/user', function () use ($app) {
	$app->post('/login', function (Request $request, Response $response, array $args) {		
	    $user = new User($this->db);
	    $result = $user->login();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
		return $response;
	});
	$app->get('/logout', function (Request $request, Response $response, array $args) {		
		return $response;
 	});
	$app->patch('/password', function (Request $request, Response $response, array $args) {		
	    $user = new User($this->db);
	    $result = $user->changePassword();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
		return $response;
	});
})->add('APIMiddleware');

$app->group('/staff', function () use ($app) {

	$app->get('/name/{id}/{type}', function (Request $request, Response $response, array $args) {		

	    $staff = new Staff($this->db);
	    $result = $staff->getStaffName($args['id'],$args['type']);	    
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;	    
	});

	$app->get('/name/{id}/{type}/{chatID}', function (Request $request, Response $response, array $args) {		
	    $staff = new Staff($this->db);
	    $result = $staff->getStaffName($args['id'],$args['type'],$args['chatID']);	    

	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;	    
	});

	$app->get('/department/{id}', function (Request $request, Response $response, array $args) {		
	    $staff = new Staff($this->db);
	    $result = $staff->getDepartment($args['id']);	    
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;	    
	});

	$app->get('/position/get', function (Request $request, Response $response, array $args) {		
	    $staff = new Staff($this->db);
	    $result = $staff->getPosition();	    
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	    
	});

	$app->get('/gender/get', function (Request $request, Response $response, array $args) {		
	    $staff = new Staff($this->db);
	    $result = $staff->getGender();	    
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;	    
	});

	$app->get('/marriage/get', function (Request $request, Response $response, array $args) {
	    $staff = new Staff($this->db);
	    $result = $staff->getMarriage();	    
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	    
	});

	$app->get('/insuredcompany/get', function (Request $request, Response $response, array $args) {		
	    $staff = new Staff($this->db);
	    $result = $staff->getInsuredcompany();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});

	$app->get('/workStatus/get', function (Request $request, Response $response, array $args) {
	    $staff = new Staff($this->db);
	    $result = $staff->getWorkStatus();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	    
	});

	$app->get('/staffType/get', function (Request $request, Response $response, array $args) {
	    $staff = new Staff($this->db);
	    $result = $staff->getStaffType();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	    
	});

	$app->get('/educationCondition/get', function (Request $request, Response $response, array $args) {
	    $staff = new Staff($this->db);
	    $result = $staff->getEducationCondition();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	    
	});

	$app->get('/staffNum/get', function (Request $request, Response $response, array $args) {
	    $staff = new Staff($this->db);
	    $result = $staff->getStaffNum();
	    $ack = array(
			'num'=>$result
		);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($ack);
		//echo $response['num'];
	    return $response;  
	});

	$app->get('/checkStaffId/{staff_id}', function (Request $request, Response $response, array $args) {
	    $staff = new Staff($this->db);
	    $result = $staff->checkStaffId($args['staff_id']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
		//echo $response['num'];
	    return $response;  
	});

	$app->post('/checkRegister/post', function (Request $request, Response $response, array $args) {
	    $staff = new Staff($this->db);
	    $result = $staff->checkRegister();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
		// echo $response;
	    return $response;   
	});

	$app->post('/register/post', function (Request $request, Response $response, array $args) {
	    $staff = new Staff($this->db);
	    $ack = $staff->register();  
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($ack);
	    return $response;   

	});

	$app->post('/modify/{staff_id}', function (Request $request, Response $response, array $args) {
	    $staff = new Staff($this->db);
	    $ack = $staff->modify($args['staff_id']);  
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($ack);
	    return $response;   
	});
})->add('APIMiddleware');
$app->group('/table', function () use ($app) {
	$app->get('/getTable', function (Request $request, Response $response, array $args) {
		
	    $table = new Table($this->db);
	    $ack = $table->getTable();
	    
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($ack);
	    return $response;
	    
	});
	$app->get('/allInfo/{staff_id}', function (Request $request, Response $response, array $args) {
		
	    $staff = new Table($this->db);
	    $result = $staff->allInfo($args['staff_id']);   
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);

	    
	    return $response;
	    
	});

	$app->get('/profile/{staff_id}', function (Request $request, Response $response, array $args) {
		
	    $staff = new Table($this->db);
	    $result = $staff->getProfile($args['staff_id']);  
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);

	    return $response; 
	});
})->add('APIMiddleware');

$app->group('/chat', function () use ($app) {

	$app->post('/delete',function (Request $request, Response $response, array $args){
		$chat = new Chat($this->db);
		$result = $chat->addDelete();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});

	
	$app->get('/init/{timestamp}', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->init();
		session_start();
		$_SESSION['last'][$args['timestamp']] = $result;
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/routine/{timestamp}/{chatID}', function (Request $request, Response $response, array $args) {
		$data = $_SESSION['last'][$args['timestamp']];
		$chat = new Chat($this->db);
		$result = $chat->routine($data,$args['chatID']);
		session_start();
    	$response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
		$_SESSION['last'][$args['timestamp']] = $result;
		$_SESSION['chat'][$_SESSION['id']][$args['chatID']] = $result;
	    return $response;
	});
	$app->get('/routine/{timestamp}/{chatID}/{limit}', function (Request $request, Response $response, array $args) {
		$data = $_SESSION['last'][$args['timestamp']];
		if(!isset($_SESSION['now'])){
			$_SESSION['now'] = 0;
		}else{
			$_SESSION['now'] = $_SESSION['now']+1;
			if($_SESSION['now']==65535)
				$_SESSION['now'] = 0;
		}
		if($args['chatID']==$data['result']['chat']['chatID'] && $data['limit'] != $args['limit']){
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
			$chat = new Chat($this->db);
			$result = $chat->routine($data,$args['chatID']);		
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
	    return $response;
	});
	$app->get('/saveChat/{chatID}', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->getSaveChat($args['chatID']);
		if(empty($result)){
			return $response;
		}
		$response = $response->withJson($result);
	    return $response;
	});
	$app->patch('/heart',function (Request $request, Response $response, array $args){
		$chat = new Chat($this->db);
		$result = $chat->patchHeart();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});

	$app->get('/commentID/{chatID}/{sendtime}', function (Request $request, Response $response, array $args) {//TODO, borrow readlist for testing
		$chat = new Chat($this->db);
		$result = $chat->getCommentID($args['chatID'],$args['sendtime']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});

	$app->get('/commentReadlist/{commentID}/{senttime}/{UID}/{chatID}', function (Request $request, Response $response, array $args) {//TODO, borrow readlist for testing
		$chat = new Chat($this->db);
		$result = $chat->getCommentReadList($args['commentID'],$args['senttime'],$args['UID'],$args['chatID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->patch('/commentReadTime/{commentID}', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->updateCommentReadTime($args['commentID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	
	$app->get('/list', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->getList();
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/list/{chatID}', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->getList($args['chatID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/department/{chatID}', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->getMemberDepartment($args['chatID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/member/{chatID}', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->getMember($args['chatID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/readlist', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->getReadList($_GET);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
        $app->get('/readlistNew', function (Request $request, Response $response, array $args) {
                $chat = new Chat($this->db);
                $result = $chat->getReadListNew($_GET);
            $response = $response->withHeader('Content-type', 'application/json' );
                $response = $response->withJson($result);
            return $response;
        });
	$app->get('/readcount', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->getReadCount($_GET);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->group('/chatroom', function () use ($app) {
		$app->get('', function (Request $request, Response $response, array $args) {
			$chat = new Chat($this->db);
			$result = $chat->getChatroom();
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->post('', function (Request $request, Response $response, array $args) {
			$chat = new Chat($this->db);
			$result = $chat->createChatroom($request->getParsedBody());
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->patch('', function (Request $request, Response $response, array $args) {
			$chat = new Chat($this->db);
			$result = $chat->updateChatroom($request->getParsedBody());
			$result = array("status"=>"success");
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->delete('', function (Request $request, Response $response, array $args) {
			$chat = new Chat($this->db);
			$result = $chat->deleteChatroom($request->getParsedBody());
			$result = array("status"=>"success");
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->get('/title/{chatID}', function (Request $request, Response $response, array $args) {
			$chat = new Chat($this->db);
			$result = $chat->getChatroomTitle($args['chatID']);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
	});
	$app->get('/content/{chatID}', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->getChatContent($args['chatID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
        $app->get('/contentNew/{chatID}', function (Request $request, Response $response, array $args) {
                $chat = new Chat($this->db);
                $result = $chat->getChatContentNew($args['chatID'],$_GET);
            $response = $response->withHeader('Content-type', 'application/json' );
                $response = $response->withJson($result);
            return $response;
        });

	$app->patch('/message', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->updateMessage($request->getParsedBody());
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/lastOnLine/{UID}', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->getLastOnLine($args['UID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});

	$app->patch('/lastReadTime', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->updateLastReadTime($request->getParsedBody());
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->post('/file/{chatID}', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->uploadFile($args['chatID'],$this->upload_directory,$request->getUploadedFiles(),false);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
	    return $response;
	});
	$app->get('/file/{fileID}', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
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
	$app->get('/fileFormat/{fileID}', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
		$result = $chat->getFileFormat($args['fileID']);
	    $response = $response->withHeader('Content-type', 'application/json' );
		$response = $response->withJson($result);
		return $response;
	});
	$app->get('/picture/{fileID}', function (Request $request, Response $response, array $args) {
		$chat = new Chat($this->db);
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
	$app->group('/class', function () use ($app) {
		$app->get('/', function (Request $request, Response $response, array $args) {
		    $class = new Chat($this->db);
		    $result = $class->getClass();   
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		    
		});
		$app->get('/{classId}/', function (Request $request, Response $response, array $args) {
		    $class = new Chat($this->db);
		    $result = $class->getClass($args['classId']);   
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		    
		});
		$app->delete('/{classId}/', function (Request $request, Response $response, array $args) {
		    $class = new Chat($this->db);
		    $result = $class->deleteClass($args['classId']);   
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		    
		});
		$app->patch('/{classId}/{chatID}/', function (Request $request, Response $response, array $args) {
		    $class = new Chat($this->db);
		    $result = $class->insertClass($args['classId'],$args['chatID']);   
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		    
		});
		$app->post('/', function (Request $request, Response $response, array $args) {
		    $class = new Chat($this->db);
		    $result = $class->addClass();   
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		    
		});
	});

	$app->group('/comment', function () use ($app) {
		$app->get('/{commentID}', function (Request $request, Response $response, array $args) {//TODO, borrow readlist for testing
			$chat = new Chat($this->db);
			$result = $chat->getComment($args['commentID']);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->post('/{commentID}', function (Request $request, Response $response, array $args) { //TODO
			$content = $request->getParsedBody()['Msg'];
			$chat = new Chat($this->db);
			$result = $chat->insertComment($args['commentID'],$content);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->post('/{commentID}/{content}', function (Request $request, Response $response, array $args) { //TODO
			$chat = new Chat($this->db);
			$result = $chat->insertComment($args['commentID'],$args['content']);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->get('/member/{commentID}/{orgSender}', function (Request $request, Response $response, array $args) { //TODO

			$chat = new Chat($this->db);
			$result = $chat->getCommentMember($args['commentID'],$args['orgSender']);

		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->get('/senter/{commentID}', function (Request $request, Response $response, array $args) { //TODO
			$chat = new Chat($this->db);
			$result = $chat->getSenter($args['commentID']);
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});

	});

	$app->group('/report', function () use ($app) {
		$app->patch('', function(Request $request, Response $response, array $args){
			$chat = new Chat($this->db);
			$result = $chat->updateReport($request->getParsedBody());
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
	});


	$app->group('/star', function () use ($app) {
		$app->get('', function(Request $request, Response $response, array $args){
			$star = new Chat($this->db);
			$result = $star->getStar();
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->post('', function(Request $request, Response $response, array $args){
			$star = new Chat($this->db);
			$result = $star->addStar();
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->delete('', function(Request $request, Response $response, array $args){
			$star = new Chat($this->db);
			$result = $star->deleteStar();
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
	});


	$app->group('/notification', function () use ($app) {
		$app->get('/', function(Request $request, Response $response, array $args){
			$notification = new Chat($this->db);
			$result = $notification->getNotification();
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->patch('/{id}', function(Request $request, Response $response, array $args){
			$notification = new Chat($this->db);
			$result = $notification->readNotification($args['id']);
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		});
		$app->post('/tag', function (Request $request, Response $response, array $args) {
		    $notification = new Chat($this->db);
		    $result = $notification->tag();   
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;
		    
		});
		$app->post('/comment', function (Request $request, Response $response, array $args) {
		    $notification = new Chat($this->db);
		    $result = $notification->commentTag();
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
		    return $response;

		});
		// $app->post('/tagDepartment', function (Request $request, Response $response, array $args) {
		//     $notification = new Chat($this->db);
		//     $result = $notification->tag();   
		//     $response = $response->withHeader('Content-type', 'application/json' );
		// 	$response = $response->withJson($result);
		//     return $response;
		    
		// });
	});
})->add('APIMiddleware');

$app->run();

?>
