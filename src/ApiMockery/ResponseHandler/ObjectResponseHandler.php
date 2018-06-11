<?php
declare(strict_types=1);

namespace ApiMockery\ResponseHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

class ObjectResponseHandler extends AbstractResponseHandler
{
    /**
     * @inheritdoc
     */
    public function execute(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $response;
    }
}
