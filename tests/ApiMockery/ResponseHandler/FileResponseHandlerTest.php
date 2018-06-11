<?php
declare(strict_types=1);

namespace ApiMockery\Tests\ResponseHandler;

use ApiMockery\Dto\Operation;
use ApiMockery\ResponseHandler\FileResponseHandler;
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
     *
     */
    public function testExecute_SetCorrectContentType()
    {

        $this->markTestSkipped();
        $this->serverRequestMock->expects(self::once())->method('hasHeader')
            ->with(FileResponseHandler::CONTENT_TYPE)
            ->willReturn(true);

        $this->serverRequestMock->expects(self::once())->method('getHeaderLine')
            ->with(FileResponseHandler::CONTENT_TYPE)
            ->willReturn('application/xml');

        $this->responseMock->expects(self::once())->method('withHeader')
            ->with(FileResponseHandler::CONTENT_TYPE, 'application/xml');

        $this->fileResponseHandler->execute($this->serverRequestMock, $this->responseMock, []);
    }


    protected function setUp()
    {
        $this->serverRequestMock = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->responseMock = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $this->operationMock = $this->getMockBuilder(Operation::class)->disableOriginalConstructor()->getMock();
        $this->fileResponseHandler = new FileResponseHandler(__DIR__, $this->operationMock);
    }
}
