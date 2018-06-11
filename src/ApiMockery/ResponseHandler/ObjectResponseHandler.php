<?php
declare(strict_types=1);

namespace ApiMockery\ResponseHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ObjectResponseHandler extends AbstractResponseHandler
{
    public function execute(ServerRequestInterface $serverRequest, ResponseInterface $response, $args): ResponseInterface
    {
        return $response;
    }
}
