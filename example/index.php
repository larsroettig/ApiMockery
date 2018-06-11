<?php
declare(strict_types=1);

$applicationBaseDir = __DIR__;

require_once $applicationBaseDir . '/../vendor/autoload.php';

use Noodlehaus\Config;

const DS = DIRECTORY_SEPARATOR;

$configPath = $applicationBaseDir . DS;
$configPath .= 'config' . DS . 'response_handler.json';

$configObject = new Config($configPath);

$configParserFactory = new \ApiMockery\ConfigParserFactory();
$configParser = $configParserFactory->create($configObject);

$routes = $configParser->parse($configObject);

$app = new  Slim\App(['settings' => ['displayErrorDetails' => true]]);

// Inject the routs in the slim app
(new ApiMockery\Router(__DIR__, $app))->setup($routes);

$app->run();



