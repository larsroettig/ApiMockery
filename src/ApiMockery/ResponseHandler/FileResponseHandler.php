<?php
declare(strict_types=1);

namespace ApiMockery\ResponseHandler;

use ApiMockery\Exception\RequestParameterException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

class FileResponseHandler extends AbstractResponseHandler
{

    const FILE_ENDING_JSON = '.json';
    const FILE_ENDING_XML = '.xml';

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws \Exception
     */
    public function execute(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $responseContentType = $this->getContentTypeByRequest($request);
        $fileEnding = $this->getFileEndingByContentType($responseContentType);

        $path = $this->getOperation()->getResponseHandler();
        $filePath = $this->getBasePath() . DIRECTORY_SEPARATOR;
        $filePath .= $this->getResponseFileName($path, $args);
        $filePath .= $fileEnding;

        if (file_exists($filePath) !== true) {
            throw new \Exception(sprintf('Mock file %s not found or readable.', $filePath));
        }

        list($statusCode, $body) = $this->getFileContentByPath($filePath, $fileEnding);
        return $this->writeRespond($response, $statusCode, $responseContentType, $body);
    }

    /**
     * @param RequestInterface $serverRequest
     * @return string
     */
    public function getContentTypeByRequest(RequestInterface $serverRequest): string
    {
        $responseType = '';

        if ($serverRequest->hasHeader(self::CONTENT_TYPE) === true) {
            $contentType = $serverRequest->getHeaderLine(self::CONTENT_TYPE);
            if ($contentType !== '') {
                $responseType = $contentType;
            }
        }

        if ($responseType === '') {
            $responseType = $this->getDefaultResponseContentType();
        }

        return $responseType;
    }

    /**
     * @return string
     */
    public function getDefaultResponseContentType(): string
    {
        return self::APPLICATION_JSON;
    }

    /**
     * @param string $responseContentType
     * @return string
     */
    public function getFileEndingByContentType(string $responseContentType): string
    {
        $responseFileType = self::FILE_ENDING_JSON;

        switch ($responseContentType) {
            case self::APPLICATION_XML:
            case self::APPLICATION_XML_RSS:
            case self::TEXT_XML:
                $responseFileType = self::FILE_ENDING_XML;
        }

        return $responseFileType;
    }

    /**
     *
     * @param string $path
     * @param array $args
     * @return mixed|string
     * @throws RequestParameterException
     */
    public function getResponseFileName(string $path, array $args)
    {
        $matches = [];
        if (preg_match_all("/{[^}]*}/", $path, $matches) !== false) {
            foreach ($matches[0] as $match) {
                $key = substr($match, 1, -1);

                if (!array_key_exists($key, $args)) {
                    throw new RequestParameterException(sprintf('Argument %s not in request.', $match));
                }

                $path = str_replace($match, $args[$key], $path);
            }
        }

        return $path;
    }

    /**
     * @param string $filePath
     * @param string $mineType
     * @return array
     * @throws \Exception
     */
    public function getFileContentByPath(string $filePath, string $mineType): array
    {
        $content = file_get_contents($filePath);

        switch ($mineType) {
            case self::FILE_ENDING_JSON:
                $content = json_decode($content);
                $statusCode = (int)$content->status;
                $body = json_encode($content->response);
                break;

            case self::FILE_ENDING_XML:
                $content = simplexml_load_string($content);
                $statusCode = (int)$content->status;
                $body = $content->response->asXML();
                break;

            default:
                throw new \Exception('handler for type' . $mineType . 'not found');
        }

        return [$statusCode, $body];
    }
}
