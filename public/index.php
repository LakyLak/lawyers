<?php

use app\core\Application;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../config.php';

$app = new Application(dirname(__DIR__), $config);

$app->router->initiateRoutes($routes);

$app->run();
