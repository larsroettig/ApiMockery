<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: roettigl
 * Date: 07.06.18
 * Time: 21:10
 */

namespace ApiMockery\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface NotFoundHandlerInterface
{
    public static function handle(Request $serverRequest, Response $response): Response;
}
