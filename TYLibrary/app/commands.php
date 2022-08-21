<?php

/** @var \Silly\Edition\PhpDi\Application $app */

$app->command("app:up", new \App\Command\UpCommand);
$app->command("app:down", new \App\Command\DownCommand);
$app->command("cache:clear", new \App\Command\CacheClearCommand);