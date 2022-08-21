<?php

/** @var \Slim\App $app */

$app->add($c->make(\App\Middleware\ExceptionHandlingMiddleware::class));
$app->add($c->make(\App\Middleware\CacheMiddleware::class));