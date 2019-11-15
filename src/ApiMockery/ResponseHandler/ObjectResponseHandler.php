<?php
declare(strict_types=1);

namespace ApiMockery\ResponseHandler;

use ApiMockery\Api\ResponseHandlerInterface;
use ApiMockery\Exception\ConfigurationMismatchException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

class ObjectResponseHandler extends AbstractResponseHandler
{
    /**
     * @inheritdoc
     */
    public function execute(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $className = $this->getOperation()->getResponseHandler();

        if (class_exists($className) === false) {
            throw new ConfigurationMismatchException('Can not find class: ' . $className);
        }

        $instance = new $className();
        if ($instance instanceof ResponseHandlerInterface) {
            return $instance->execute($request, $response, $args);
        }

        throw new \Exception(sprintf('Class %s impelments not ResponseHandlerInterface', get_class($instance)));
    }
}
