<?php
declare(strict_types=1);

namespace ApiMockery\ResponseHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

class FileResponseHandler extends AbstractResponseHandler
{


    /**
     * @inheritdoc
     */
    private const JSON = '.json';
    private const XML = '.xml';
    /**
     * @var string
     */
    private $responseContentType;

    public function execute(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->initResponseType($request);

        try {
            $responseFileName = $this->getResponseFileName($this->getOperation()->getResponseHandler(), $args);
        } catch (\Throwable $exception) {
            // @todo handle error case
        }

        return $this->writeFileRespond($response, $responseFileName);
    }

    protected function initResponseType(RequestInterface $serverRequest): void
    {
        if ($serverRequest->hasHeader(self::CONTENT_TYPE) === true) {
            $contentType = $serverRequest->getHeaderLine(self::CONTENT_TYPE);
            if ($contentType !== null || $contentType !== '') {
                $this->setResponseContentType($contentType);
            }
        }

        // @todo use first entry from  $this->getOperation()->getProduces() if it is set
        if ($this->getResponseContentType() === '') {
            $this->setResponseContentType(self::APPLICATION_JSON);
        }
    }

    public function getResponseContentType(): string
    {
        return $this->responseContentType;
    }

    public function setResponseContentType(string $responseContentType): void
    {
        $this->responseContentType = $responseContentType;
    }

    /**
     * @param array $args
     * @return mixed|string
     * @throws \Exception
     */
    public function getResponseFileName(string $responseHandlerPath, array $args)
    {
        $matches = [];
        if (preg_match_all("/{[^}]*}/", $responseHandlerPath, $matches) !== false) {
            foreach ($matches[0] as $match) {
                $key = substr($match, 1, -1);
                if (!array_key_exists($key, $args)) {
                    throw new \Exception(sprintf('Argument %s not in request.', $match));
                }

                $responseHandlerPath = str_replace($match, $args[$key], $responseHandlerPath);
            }
        }

        return $responseHandlerPath;
    }

    /**
     * @param string $responseFileName
     * @return ResponseInterface
     * @throws \Exception
     */
    protected function writeFileRespond(ResponseInterface $response, string $responseFileName): ResponseInterface
    {
        $filePath = $this->getBasePath() . DIRECTORY_SEPARATOR;
        $filePath .= $responseFileName;
        $filePath .= $this->getResponseFileType();

        if (file_exists($filePath) !== true) {
            /** @todo add default api error response possbilty  * */
            throw new \Exception(sprintf('Mock %s not found or readable.', $responseFileName));
        }

        list($statusCode, $body) = $this->getFileContentByPath($filePath);
        return $this->writeRespond($response, $statusCode, $this->getResponseContentType(), $body);
    }

    /**
     * @return string
     */
    public function getResponseFileType(): string
    {
        $responseFileType = self::JSON;

        switch ($this->getResponseContentType()) {
            case self::APPLICATION_XML:
            case self::APPLICATION_XML_RSS:
            case self::TEXT_XML:
                $responseFileType = self::XML;
        }

        return $responseFileType;
    }

    /**
     * @return array
     */
    protected function getFileContentByPath(string $filePath): array
    {
        $content = file_get_contents($filePath);

        switch ($this->getResponseFileType()) {
            case self::JSON:
                $content = json_decode($content);
                $statusCode = (int)$content->status;
                $body = json_encode($content->response);
                break;

            case self::XML:
                $content = simplexml_load_string($content);
                $statusCode = (int)$content->status;
                $body = $content->response->asXML();
                break;

            default:
                $statusCode = 404;
                $body = '';
        }

        return [$statusCode, $body];
    }
}
