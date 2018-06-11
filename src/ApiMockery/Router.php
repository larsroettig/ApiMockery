<?php
declare(strict_types=1);

namespace ApiMockery;

use ApiMockery\Api\ResponseHandlerInterface;
use ApiMockery\Dto\Operation;
use ApiMockery\Dto\Path;
use ApiMockery\Exception\ConfigurationMismatchException;
use ApiMockery\ResponseHandler\FileResponseHandler;
use ApiMockery\ResponseHandler\ObjectResponseHandler;
use Slim\App;

class Router
{
    private $responseHandler =
        [
            'object' => ObjectResponseHandler::class,
            'files' => FileResponseHandler::class,
        ];

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var App
     */
    private $app;

    /**
     * Router constructor.
     */
    public function __construct(string $basePath, App $app)
    {
        $this->basePath = $basePath;
        $this->app = $app;
    }

    /**
     * @param Path[] $routes
     */
    public function setup(array $routes): void
    {
        foreach ($routes as $route) {
            $path = $route->getBaseUrl();
            $router = $this;

            foreach ($route->getOperations() as $operation) {
                $method = $operation->getType();
                $this->app->map([$method], $path, $this->getOperationClosure($router, $operation));
            }
        }
    }

    /**
     * @throws ConfigurationMismatchException
     */
    public function getResponseHandlerByType(Operation $operation): ResponseHandlerInterface
    {
        $type = $operation->getResponseHandlerType();

        if (isset($this->responseHandler[$type]) === false) {
            $msg = sprintf('Response Handler from type %s not impelemented.', $type);
            throw  new ConfigurationMismatchException($msg);
        }

        $type = $this->responseHandler[$type];
        return new $type($this->basePath, $operation);
    }

    public function getResponseHandler(): array
    {
        return $this->responseHandler;
    }

    public function setResponseHandler(array $responseHandler): void
    {
        $this->responseHandler = $responseHandler;
    }

    protected function getOperationClosure(self $router, Operation $operation): \Closure
    {
        return function ($request, $response, $args) use ($router, $operation) {
            return $router->getResponseHandlerByType($operation)->execute($request, $response, $args);
        };
    }
}
