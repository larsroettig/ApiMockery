<?php
declare(strict_types=1);

namespace ApiMockery;

use ApiMockery\Api\ResponseHandlerFactoryInterface as ResponseHandlerFactory;
use ApiMockery\Dto\Operation;
use ApiMockery\Dto\Path;
use Slim\App;

class Application
{

    /**
     * @var App
     */
    private $app;

    /**
     * @var ResponseHandlerFactory
     */
    private $responseHandlerFactory;

    /**
     * Router constructor.
     * @param App $app
     * @param ResponseHandlerFactory $responseHandlerFactory
     */
    public function __construct(App $app, ResponseHandlerFactory $responseHandlerFactory)
    {
        $this->app = $app;
        $this->responseHandlerFactory = $responseHandlerFactory;
    }

    /**
     * @param Path[] $routes
     */
    public function setup(array $routes)
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
        return function ($request, $response, $args) use ($operation) {
            if (!\is_array($args)) {
                $args = [];
            }
            $responseHandler = $this->responseHandlerFactory->create($operation);
            return $responseHandler->execute($request, $response, $args);
        };
    }
}
