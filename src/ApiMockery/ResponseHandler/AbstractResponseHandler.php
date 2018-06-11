<?php
declare(strict_types=1);

namespace ApiMockery\ResponseHandler;

use ApiMockery\Api\ResponseHandlerInterface;
use ApiMockery\Dto\Operation;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractResponseHandler implements ResponseHandlerInterface
{
    const CONTENT_TYPE = 'Content-Type';
    const APPLICATION_JSON = "application/json";
    const APPLICATION_X_JAVASCRIPT = "application/x-javascript";
    const APPLICATION_JAVASCRIPT = "application/javascript";
    const TEXT_XML = "text/xml";
    const APPLICATION_XML = "application/xml";
    const APPLICATION_XML_RSS = "application/xml+rss";
    const TEXT_JAVASCRIPT = "text/javascript";

    /**
     * @var array
     */
    protected $relevantMimeTypes = [
        self::APPLICATION_JSON,
        self::APPLICATION_X_JAVASCRIPT,
        self::APPLICATION_JAVASCRIPT,
        self::TEXT_XML,
        self::APPLICATION_XML,
        self::APPLICATION_XML_RSS,
        self::TEXT_JAVASCRIPT
    ];
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
     * @param Operation $operation
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

    /**
     * @return array
     */
    public function getRelevantMimeTypes(): array
    {
        return $this->relevantMimeTypes;
    }

    /**
     * @param array $relevantMimeTypes
     */
    public function setRelevantMimeTypes(array $relevantMimeTypes): void
    {
        $this->relevantMimeTypes = $relevantMimeTypes;
    }


    /**
     * Write the
     * @param string $contentType
     * @param string $body
     */
    protected function writeRespond(
        ResponseInterface $response,
        int $statusCode,
        string $contentType,
        string $body
    ): ResponseInterface {
        $response->getBody()->write($body);
        return $response->withStatus($statusCode)->withHeader(self::CONTENT_TYPE, $contentType);
    }
}
