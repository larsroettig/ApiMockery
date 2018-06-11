<?php
declare(strict_types=1);

namespace ApiMockery\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ResponseHandlerInterface
{
    public function execute(
        ServerRequestInterface $serverRequest,
        ResponseInterface $response,
        $args
    ): ResponseInterface;
}
