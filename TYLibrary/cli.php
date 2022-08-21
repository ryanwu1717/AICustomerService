<?php

CONST APP_ROOT = __DIR__;

require APP_ROOT . '/vendor/autoload.php';
(new \Dotenv\Dotenv(APP_ROOT))->load();

$app = new \Silly\Edition\PhpDi\Application();
$c = $app->getContainer();

require APP_ROOT . '/app/dependencies.php';
require APP_ROOT . '/app/commands.php';

$app->run();