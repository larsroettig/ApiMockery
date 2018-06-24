<?php

namespace ApiMockery\Tests;

use ApiMockery\ConfigParserFactory;
use ApiMockery\Dto\Operation;
use ApiMockery\Exception\ConfigurationMismatchException;
use ApiMockery\ResponseHandler\FileResponseHandler;
use ApiMockery\ResponseHandlerFactory;
use Noodlehaus\ConfigInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigParserFactoryTest
 * @package ApiMockery\Tests
 * @cover ConfigParserFactory
 */
class ResponseHandlerFactoryTest extends TestCase
{
    /**
     * @var ResponseHandlerFactory
     */
    private $responseHandlerFactory;

    /**
     * @expectedException  \ApiMockery\Exception\ConfigurationMismatchException
     */
    public function testCreateWithException()
    {
        $operationGet = new Operation('GET',
            'getTest',
            ['text/xml'],
            'newType',
            'responses/test'
        );
        $this->responseHandlerFactory->create($operationGet);
    }

    public function testCreateWithFileHandler()
    {
        $operationGet = new Operation('GET',
            'getTest',
            ['text/xml'],
            'files',
            'responses/test'
        );

        $type = $this->responseHandlerFactory->create($operationGet);

        self::assertInstanceOf(FileResponseHandler::class, $type);
    }

    /**
     *
     */
    protected function setUp()
    {
        $this->responseHandlerFactory = new ResponseHandlerFactory('foo');
    }
}
