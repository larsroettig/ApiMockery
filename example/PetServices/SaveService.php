<?php

namespace PetServices;

use ApiMockery\Api\MockServiceInterface;
use ApiMockery\Api\ResponseHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SaveService implements ResponseHandlerInterface
{
    public function execute(ServerRequestInterface $serverRequest, ResponseInterface $response): ResponseInterface
    {
        return $response;
    }
}
