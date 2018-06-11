<?php
declare(strict_types=1);

namespace ApiMockery\ResponseHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

class FileResponseHandler extends AbstractResponseHandler
{
    /**
     * @return ResponseInterface
     */
    const CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    private $responseContentType;

    public function execute(RequestInterface $serverRequest, ResponseInterface $response, $args): ResponseInterface
    {
        $this->initResponseType($serverRequest);
        $response->getBody()->write($this->responseContentType);

        return $response;
    }

    public function getResponseContentType(): string
    {
        return $this->responseContentType;
    }

    public function setResponseContentType(string $responseContentType): void
    {
        $this->responseContentType = $responseContentType;
    }

    protected function initResponseType(RequestInterface $serverRequest): void
    {
        if ($serverRequest->hasHeader(self::CONTENT_TYPE) === true) {
            $contentType = $serverRequest->getHeaderLine(self::CONTENT_TYPE);
            if ($contentType !== '') {
                $this->setResponseContentType($contentType);
            }
        }

        // @todo use first entry from  $this->getOperation()->getProduces() if it is set
        if ($this->getResponseContentType() === '') {
            $this->setResponseContentType('application/json');
        }
    }

    protected function findResponseFile(string $mineType): void
    {
    }

    protected function responseFileNotFound(string $mineType): void
    {
    }
}
