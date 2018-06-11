<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Noodlehaus\Config;

$configPath = __DIR__ . DS . 'config' . DS;
$configPath .= 'response_handler.json';

$configObject = new Config($configPath);

$configParserFactory = new \ApiMockery\ConfigParserFactory();
$configParser = $configParserFactory->create($configObject);

$routes = $configParser->parse($configObject);
