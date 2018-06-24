<?php declare(strict_types=1);

namespace PetServices;

use ApiMockery\Api\ResponseHandlerInterface;
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
