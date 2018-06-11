<?php
declare(strict_types=1);

namespace ApiMockery\Dto;

/**
 * Class ConfigFactory
 * @package ApiMockery
 */
class Operation
{
    /**
     * @var string
     */
    private $operationId;

    /**
     * @var array
     */
    private $produces;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $responseHandlerType;

    /**
     * @var string
     */
    private $responseHandler;

    public function __construct(
        string $type,
        string $operationId,
        array $produces,
        string $responseHandlerType,
        string $responseHandler
    ) {
        $this->operationId = $operationId;
        $this->produces = $produces;
        $this->type = $type;
        $this->responseHandler = $responseHandler;
        $this->responseHandlerType = $responseHandlerType;
    }

    public function getOperationId(): string
    {
        return $this->operationId;
    }

    public function getProduces(): array
    {
        return $this->produces;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getResponseHandler(): string
    {
        return $this->responseHandler;
    }

    public function getResponseHandlerType(): string
    {
        return $this->responseHandlerType;
    }
}
