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
     */
    public function __construct(string $basePath, Operation $operation)
    {
        $this->basePath = $basePath;
        $this->operation = $operation;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @return Operation
     * @codeCoverageIgnore
     */
    public function getOperation(): Operation
    {
        return $this->operation;
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getRelevantMimeTypes(): array
    {
        return $this->relevantMimeTypes;
    }

    /**
     * @param array $relevantMimeTypes
     * @return void
     * @codeCoverageIgnore
     */
    public function setRelevantMimeTypes(array $relevantMimeTypes)
    {
        $this->relevantMimeTypes = $relevantMimeTypes;
    }


    /**
     * @param ResponseInterface $response
     * @param int $statusCode
     * @param string $contentType
     * @param string $body
     * @return ResponseInterface
     */
    protected function writeRespond(
        ResponseInterface $response,
        int $statusCode,
        string $contentType,
        string $body
    ): ResponseInterface {
        $response->getBody()->write($body);
        $response->withStatus($statusCode);
        $response->withHeader(self::CONTENT_TYPE, $contentType);
        return $response;
    }
}
