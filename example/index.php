<?php
declare(strict_types=1);

const DS = DIRECTORY_SEPARATOR;

require_once __DIR__ . DS . 'bootstrap.php';

$app = new Slim\App(['settings' => ['displayErrorDetails' => true]]);

// Inject the routs in the slim app
(new ApiMockery\Router(__DIR__, $app))->setup($routes);

$app->run();
