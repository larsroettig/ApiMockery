<?php
declare(strict_types=1);

namespace ApiMockery\Dto;

/**
 * Class ConfigFactory
 * @package ApiMockery
 * @codeCoverageIgnore
 */
class Path
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * Http methods as array.
     *
     * @var Operation[]
     */
    private $operations;

    /**
     * Path constructor.
     * @param Operation[] $operations
     */
    public function __construct(string $baseUrl, array $operations)
    {
        $this->baseUrl = $baseUrl;
        $this->operations = $operations;
    }

    /**
     * @return array|Operation[]
     */
    public function getOperations(): array
    {
        return $this->operations;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
