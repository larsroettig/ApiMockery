<?php

namespace PetServices;

use ApiMockery\Api\MockServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PetService
 * @package Sample
 */
class GetListService implements ResponseHandlerInterface
{
    public function execute(ServerRequestInterface $serverRequest, ResponseInterface $response): ResponseInterface
    {
        return $response;
    }
}