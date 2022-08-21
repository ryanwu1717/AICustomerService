<?php
/** @var \Slim\App $app */

$app->get("/", ['\App\Controller\MainController', 'getFirst']);
$app->get("/home", ['\App\Controller\MainController', 'getHome']);
$app->get("/ptt/news", ['\App\Controller\MainController', 'getPttNews']);
$app->post("/ptt/news", ['\App\Controller\MainController', 'updatePttNews']);
$app->get("/line/lastUpdated", ['\App\Controller\MainController', 'getLineUpdate']);
$app->post("/line/lastUpdated", ['\App\Controller\MainController', 'updateLineUpdate']);
$app->get("/chatData", ['\App\Controller\MainController', 'getChatData']);
$app->post("/chatData", ['\App\Controller\MainController', 'updateChatData']);




$app->group('/user', function () use ($app) {
	$app->post("", ['\App\Controller\Users', 'addNewUser']);
	$app->post("/{UID}", ['\App\Controller\Users', 'setSession']);


});


$app->group('/reply', function () use ($app) {
	$app->get("/title", ['\App\Controller\Reply', 'getTitle']);
	$app->post("/appear", ['\App\Controller\Reply', 'addAppear']);
	$app->get("/appear/count", ['\App\Controller\Reply', 'getAppearCount']);
	$app->patch("/appear/count", ['\App\Controller\Reply', 'updateAppearCount']);

	$app->get("/lasttime", ['\App\Controller\Reply', 'getLasttime']);


});













