<?php
declare(strict_types=1);

namespace ApiMockery;

use ApiMockery\Api\ResponseHandlerFactoryInterface;
use ApiMockery\Api\ResponseHandlerInterface;
use ApiMockery\Dto\Operation;
use ApiMockery\Exception\ConfigurationMismatchException;
use ApiMockery\ResponseHandler\FileResponseHandler;
use ApiMockery\ResponseHandler\ObjectResponseHandler;

class ResponseHandlerFactory implements ResponseHandlerFactoryInterface
{
    /**
     * @var array
     */
    protected $responseHandler =
        [
            'object' => ObjectResponseHandler::class,
            'files' => FileResponseHandler::class,
        ];

    /**
     * @var string
     */
    private $basePath;

    /**
     * ResponseHandlerFactory constructor.
     * @param string $basePath
     */
    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @inheritdoc
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-suppress InvalidStringClass
     */
    public function create(Operation $operation): ResponseHandlerInterface
    {
        $type = $operation->getResponseHandlerType();

        if (isset($this->responseHandler[$type]) === false) {
            $msg = sprintf('Response Handler from type %s not implemented.', $type);
            throw  new ConfigurationMismatchException($msg);
        }

        $type = $this->responseHandler[$type];
        return new $type($this->basePath, $operation);
    }
}