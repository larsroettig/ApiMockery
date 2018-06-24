<?php
declare(strict_types=1);

const DS = DIRECTORY_SEPARATOR;

require_once __DIR__ . DS . 'bootstrap.php';

$app = new Slim\App(['settings' => ['displayErrorDetails' => true]]);
$responseHandlerFactory = new \ApiMockery\ResponseHandlerFactory(__DIR__);

// Inject the routs in the slim app
(new ApiMockery\Application($app, $responseHandlerFactory))->setup($routes);

$app->run();
