<?php
declare(strict_types=1);

namespace ApiMockery\Tests\ResponseHandler;

use ApiMockery\Dto\Operation;
use ApiMockery\Exception\RequestParameterException;
use ApiMockery\ResponseHandler\FileResponseHandler;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class FileResponseHandlerTest extends TestCase
{
    /**
     * @var FileResponseHandler
     */
    private $fileResponseHandler;

    /**
     * @var Operation | \PHPUnit_Framework_MockObject_MockObject
     */
    private $operationMock;

    /**
     * @var ServerRequestInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $serverRequestMock;

    /**
     * @var ResponseInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $responseMock;
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     *
     */
    public function testExecute_getContentType()
    {
        $this->serverRequestMock->expects(self::once())->method('hasHeader')
            ->with(FileResponseHandler::CONTENT_TYPE)
            ->willReturn(true);

        $this->serverRequestMock->expects(self::once())->method('getHeaderLine')
            ->with(FileResponseHandler::CONTENT_TYPE)
            ->willReturn('application/xml');

        $result = $this->fileResponseHandler->getContentTypeByRequest($this->serverRequestMock);
        self::assertEquals('application/xml', $result);
    }

    /**
     * @expectedException \Exception
     */
    public function testFileContentByPath_Fail()
    {
        $tes = vfsStream::newFile('test.html');
        $tes->setContent('')->at($this->root);

        $this->fileResponseHandler->getFileContentByPath($tes->url(), 'hmtl');
    }

    public function testExecute_getContentTypeNoSetInRequest()
    {
        $this->serverRequestMock->expects(self::once())->method('hasHeader')
            ->with(FileResponseHandler::CONTENT_TYPE)
            ->willReturn(false);

        $this->serverRequestMock->expects(self::never())->method('getHeaderLine');
        $result = $this->fileResponseHandler->getContentTypeByRequest($this->serverRequestMock);

        self::assertEquals($this->fileResponseHandler->getDefaultResponseContentType(), $result);
    }

    public function testGetResponseFileName_OneArgument()
    {
        $result = $this->fileResponseHandler->getResponseFileName('/test/test{id}', ['id' => 12]);
        self::assertEquals('/test/test12', $result);
    }

    public function testGetResponseFileName_TwoArgument()
    {
        $result = $this->fileResponseHandler->getResponseFileName('/{endpoint}/test{id}', [
            'endpoint' => 'test',
            'id' => 12
        ]);

        self::assertEquals('/test/test12', $result);
    }

    public function testGetFileEndingByContentType()
    {
        $result = $this->fileResponseHandler->getFileEndingByContentType(
            FileResponseHandler::APPLICATION_XML_RSS
        );

        self::assertEquals(FileResponseHandler::FILE_ENDING_XML, $result);

    }


    /**
     * @expectedException \ApiMockery\Exception\RequestParameterException
     */
    public function testGetResponseFileName_MissingArgument()
    {
        $result = $this->fileResponseHandler->getResponseFileName('/{endpoint}/test{id}', [
            'id' => 12
        ]);

        self::assertEquals('/test/test12', $result);
    }


    public function testExecute_Json()
    {
        $jsonContent = '{
          "status": 200,
          "response": {
            "properties": {
              "id": "1",
              "name": "Eagle"
            }
          }
        }';

        $tes = vfsStream::newFile('test.json');
        $tes->setContent($jsonContent)->at($this->root);

        $this->operationMock->expects(self::once())
            ->method('getResponseHandler')
            ->willReturn('test');

        $mock = $this->getMockBuilder(StreamInterface::class)->getMock();
        $mock->expects(self::once())
            ->method('write')
            ->with('{"properties":{"id":"1","name":"Eagle"}}');

        $this->responseMock->expects(self::once())
            ->method('getBody')
            ->willReturn($mock);

        $this->fileResponseHandler->execute($this->serverRequestMock, $this->responseMock, []);
    }

    public function testExecute_XML()
    {
        $xmlContent = '<?xml version="1.0" encoding="UTF-8" ?>
            <body>
                <status>200</status>
                <response>
                    <properties>
                        <id>1</id>
                        <name>Eagle</name>
                    </properties>
                </response>
        </body>';

        $tes = vfsStream::newFile('test.xml');
        $tes->setContent($xmlContent)->at($this->root);

        $this->operationMock->expects(self::once())
            ->method('getResponseHandler')
            ->willReturn('test');

        $mock = $this->getMockBuilder(StreamInterface::class)->getMock();
        $mock->expects(self::once())
            ->method('write')
            ->with('<response>
                    <properties>
                        <id>1</id>
                        <name>Eagle</name>
                    </properties>
                </response>');

        $this->responseMock->expects(self::once())
            ->method('getBody')
            ->willReturn($mock);

        $this->serverRequestMock->expects(self::once())->method('hasHeader')
            ->with(FileResponseHandler::CONTENT_TYPE)
            ->willReturn(true);

        $this->serverRequestMock->expects(self::once())->method('getHeaderLine')
            ->with(FileResponseHandler::CONTENT_TYPE)
            ->willReturn('application/xml');

        $this->fileResponseHandler->execute($this->serverRequestMock, $this->responseMock, []);
    }

    /**
     * @expectedException \Exception
     */
    public function testExecute_FailMineTypeHMTL()
    {
        $xmlContent = '<?xml version="1.0" encoding="UTF-8" ?>
            <body>
                <status>200</status>
                <response>
                    <properties>
                        <id>1</id>
                        <name>Eagle</name>
                    </properties>
                </response>
        </body>';

        $tes = vfsStream::newFile('test.html');
        $tes->setContent($xmlContent)->at($this->root);

        $this->operationMock->expects(self::once())
            ->method('getResponseHandler')
            ->willReturn('test');

        $this->serverRequestMock->expects(self::once())->method('hasHeader')
            ->with(FileResponseHandler::CONTENT_TYPE)
            ->willReturn(true);

        $this->serverRequestMock->expects(self::once())->method('getHeaderLine')
            ->with(FileResponseHandler::CONTENT_TYPE)
            ->willReturn('application/html');

        $this->fileResponseHandler->execute($this->serverRequestMock, $this->responseMock, []);
    }


    protected function setUp()
    {
        $this->root = vfsStream::setup();

        $this->serverRequestMock = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->responseMock = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $this->operationMock = $this->getMockBuilder(Operation::class)->disableOriginalConstructor()->getMock();
        $this->fileResponseHandler = new FileResponseHandler($this->root->url(), $this->operationMock);
    }
}
