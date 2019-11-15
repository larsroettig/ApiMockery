<?php
declare(strict_types=1);

namespace ApiMockery;

use ApiMockery\Api\ResponseHandlerFactoryInterface ;
use ApiMockery\Dto\Operation;
use ApiMockery\Dto\Path;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Slim\App;
use function is_array;

class Application
{

    /**
     * @var App
     */
    private $app;

    /**
     * @var ResponseHandlerFactoryInterface
     */
    private $responseHandlerFactory;

    /**
     * Router constructor.
     * @param App $app
     * @param ResponseHandlerFactoryInterface $responseHandlerFactory
     */
    public function __construct(App $app, ResponseHandlerFactoryInterface $responseHandlerFactory)
    {
        $this->app = $app;
        $this->responseHandlerFactory = $responseHandlerFactory;
    }

    /**
     * @param Path[] $routes
     */
    public function setup(array $routes) : void
    {
        foreach ($routes as $route) {
            $path = $route->getBaseUrl();
            foreach ($route->getOperations() as $operation) {
                $method = $operation->getType();
                $this->app->map([$method], $path, $this->getOperationClosure($operation));
            }
        }
    }

    /**
     * @param Operation $operation
     * @return \Closure
     * @codeCoverageIgnore
     */
    protected function getOperationClosure(Operation $operation): \Closure
    {
        $responseHandlerFactory = $this->responseHandlerFactory;

        return function (RequestInterface $request, ResponseInterface $response, array $args = null) use (
            $operation,
            $responseHandlerFactory
        ) {
            if (!is_array($args)) {
                $args = [];
            }

            return $responseHandlerFactory->create($operation)->execute($request, $response, $args);
        };
    }
}
