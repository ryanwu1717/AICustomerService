<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require_once __DIR__.'/../model/management.php';
$container['ManagementViewMiddleware'] = function($container) {
    return new ManagementViewMiddleware($container->db);
};
$container['ManagementMiddleware'] = function($container) {
    return new ManagementMiddleware($container->db);
};

class ManagementMiddleware
{
	private $conn;
	function __construct($db){
		$this->conn = $db;
	}
    public function __invoke($request, $response, $next)
    {
    	if(!is_null($request->getAttribute('viewParam'))){
			$viewParam = $request->getAttribute('viewParam');			
    	}else{
			$viewParam = array();		
    	}
		$viewParam['url'] = '/management';
		$viewParam['placeholderAccount'] = '管理員帳號';
		$request = $request->withAttribute('viewParam', $viewParam);
    	$response = $next($request, $response);
        return $response;
    }
}
class ManagementViewMiddleware
{
	private $conn;
	function __construct($db){
		$this->conn = $db;
	}
    public function __invoke($request, $response, $next)
    {
    	if(isset($_SESSION['management']['id'])){
    		$management = new Management($this->conn);
	    	$name = $management->getName();
	    	if(!is_null($request->getAttribute('viewParam'))){
				$viewParam = $request->getAttribute('viewParam');			
	    	}else{
				$viewParam = array();		
	    	}
	    	if(count($name)==1){
	    		$viewParam['name'] = $name[0]['staff_name'];
	    	}
    		$request = $request->withAttribute('viewParam', $viewParam);
        	$response = $next($request, $response);
    	}
    	else{
			return $response->withRedirect('/management/login', 301);
    	}
        return $response;
    }
}

$app->group('/management', function () use ($app) {
	$app->group('', function () use ($app) {
		$app->get('/', function (Request $request, Response $response, array $args) {		
			return $response->withRedirect('/management/home', 301);
		});
		$app->group('', function () use ($app) {
			$app->get('/home', function (Request $request, Response $response, array $args) {	
				$viewParam = $request->getAttribute('viewParam');	
				return $this->view->render($response, '/tables.php', $viewParam);
			});
			$app->get('/register', function (Request $request, Response $response, array $args) {	
				$viewParam = $request->getAttribute('viewParam');		
				return $this->view->render($response, '/register.php', $viewParam);
			});
			$app->get('/deleteChat', function (Request $request, Response $response, array $args) {	
 				$viewParam = $request->getAttribute('viewParam');
				return $this->view->render($response, '/deleteChat.php', $viewParam);
 			});

			$app->get('/table', function (Request $request, Response $response, array $args) {	
				$viewParam = $request->getAttribute('viewParam');		
				return $this->view->render($response, '/tables.php', $viewParam);
			});
			$app->get('/modify', function (Request $request, Response $response, array $args) {	
				$viewParam = $request->getAttribute('viewParam');		
				return $this->view->render($response, '/register.php', $viewParam);
			});
			$app->get('/add', function (Request $request, Response $response, array $args) {	
				$viewParam = $request->getAttribute('viewParam');		
				return $this->view->render($response, '/add.php', $viewParam);
			});
		})->add('ManagementViewMiddleware');
		$app->get('/login', function (Request $request, Response $response, array $args) {	
			session_destroy();
			$viewParam = $request->getAttribute('viewParam');		
			return $this->view->render($response, '/login.php', $viewParam);
		});
	})->add('ManagementMiddleware');

	$app->group('/item', function () use ($app) {
		$app->post('/add/post', function (Request $request, Response $response, array $args) {
			$add = new Add($this->db);
			$result = $add->addItem();
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);  
		    return $response;
		});
		$app->post('/delete/post', function (Request $request, Response $response, array $args) {
			$add = new Add($this->db);
			$result = $add->deleteItem();
			$response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);  
		    return $response;
		});
	});

	$app->group('/profile', function () use ($app) {
		$app->delete('', function (Request $request, Response $response, array $args) {	
		    $table = new Tables($this->db);
		    $result = $table->deleteStaff();
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
			return $response;
		});
	});

	$app->group('/user', function () use ($app) {
		$app->post('/login', function (Request $request, Response $response, array $args) {		
		    $management = new Management($this->db);
		    $result = $management->login();
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
			return $response;
		});
		$app->get('/logout', function (Request $request, Response $response, array $args) {		
			return $response;
	 	});
	});

$app->group('/chat', function () use ($app) {
		$app->delete('', function (Request $request, Response $response, array $args) {		
		    $checkin = new ChatManage($this->db);
		    $result = $checkin->deleteChat();
		    $response = $response->withHeader('Content-type', 'application/json' );
			$response = $response->withJson($result);
			return $response;
		});
	});
});
?>
