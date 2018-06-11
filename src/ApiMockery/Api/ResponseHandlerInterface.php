<?php
declare(strict_types=1);

namespace ApiMockery\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

interface ResponseHandlerInterface
{
    /**
     * @param array $args
     */
    public function execute(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface;
}
