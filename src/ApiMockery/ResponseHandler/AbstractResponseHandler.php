<?php
declare(strict_types=1);

namespace ApiMockery\ResponseHandler;

use ApiMockery\Api\ResponseHandlerInterface;
use ApiMockery\Dto\Operation;

abstract class AbstractResponseHandler implements ResponseHandlerInterface
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var Operation
     */
    private $operation;

    /**
     * AbstractResponseHandler constructor.
     */
    public function __construct(string $basePath, Operation $operation)
    {
        $this->basePath = $basePath;
        $this->operation = $operation;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getOperation(): Operation
    {
        return $this->operation;
    }
}
