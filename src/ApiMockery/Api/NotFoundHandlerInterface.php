<?php
declare(strict_types=1);

namespace ApiMockery\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface NotFoundHandlerInterface
{
    public static function handle(Request $serverRequest, Response $response): Response;
}
