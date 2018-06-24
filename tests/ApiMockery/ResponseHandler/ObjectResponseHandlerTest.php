<?php
declare(strict_types=1);

namespace ApiMockery\ResponseHandler;

use ApiMockery\Api\ResponseHandlerInterface;
use ApiMockery\Dto\Operation;
use ApiMockery\Exception\ConfigurationMismatchException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

class ObjectResponseHandlerTest extends TestCase
{
    /**
     * @var ObjectResponseHandler
     */
    private $objectResponseHandler;

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


    public function testExecute_Successfully()
    {
        $this->getMockBuilder(ResponseHandlerInterface::class)
            ->setMockClassName('TestResponseHandler')
            ->getMock();

        $this->operationMock->expects(self::once())
            ->method('getResponseHandler')
            ->willReturn('TestResponseHandler');

        $this->objectResponseHandler->execute($this->serverRequestMock, $this->responseMock, []);
    }

    /**
     * @expectedException  \Exception
     */
    public function testExecute_WrongInterface()
    {
        $this->getMockBuilder('ResponseHandlerFoo')
            ->getMock();

        $this->operationMock->expects(self::once())
            ->method('getResponseHandler')
            ->willReturn('ResponseHandlerFoo');

        $this->objectResponseHandler->execute($this->serverRequestMock, $this->responseMock, []);
    }



    /**
     * @expectedException  \Exception
     */
    public function testExecute_WrongConfig()
    {
        $this->operationMock->expects(self::once())
            ->method('getResponseHandler')
            ->willReturn('wrongclass');

        $this->objectResponseHandler->execute($this->serverRequestMock, $this->responseMock, []);
    }

    protected function setUp()
    {
        $this->root = vfsStream::setup();

        $this->serverRequestMock = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->responseMock = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $this->operationMock = $this->getMockBuilder(Operation::class)->disableOriginalConstructor()->getMock();
        $this->objectResponseHandler = new ObjectResponseHandler($this->root->url(), $this->operationMock);
    }
}
