<?php
declare(strict_types=1);

namespace ApiMockery\Dto;

/**
 * Class ConfigFactory
 * @package ApiMockery
 * @codeCoverageIgnore
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

    /**
     * Operation constructor.
     * @param array $produces
     */
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

    /**
     * @return string
     */
    public function getOperationId(): string
    {
        return $this->operationId;
    }

    /**
     * @return array
     */
    public function getProduces(): array
    {
        return $this->produces;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getResponseHandler(): string
    {
        return $this->responseHandler;
    }

    /**
     * @return string
     */
    public function getResponseHandlerType(): string
    {
        return $this->responseHandlerType;
    }
}
