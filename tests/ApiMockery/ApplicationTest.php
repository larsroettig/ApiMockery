<?php
/**
 * ApplicationTest
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    juan.alonso@staempfli.com
 */

namespace ApiMockery\Tests;

use ApiMockery\Api\ResponseHandlerFactoryInterface;
use ApiMockery\Api\ResponseHandlerInterface;
use ApiMockery\Application;
use ApiMockery\Dto\Operation;
use ApiMockery\Dto\Path;
use PHPStan\Testing\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Slim\App;

/**
 * Class ApplicationTest
 * @package ApiMockery\Tests
 */
class ApplicationTest extends TestCase
{

    /**
     * @var MockObject | App
     */
    private $slimAppMock;

    /**
     * @var MockObject | ResponseHandlerInterface
     */
    private $responseHandlerMock;

    /**
     * @var Application
     */
    private $application;


    public function testRegisterResponseHandler()
    {
        $operationGet = new Operation('GET',
            'getTest',
            ['text/xml'],
            'files',
            'responses/test'
        );



        $path1 = new Path('foo/test', [$operationGet]);
        $testClojure = function () {
        };

        $this->slimAppMock->expects(self::once())
            ->method('map')
            ->with(['GET'], 'foo/test', $testClojure);

        $this->application->setup([$path1]);


    }


    protected function setUp()
    {

        $this->slimAppMock = $this->getMockBuilder(App::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseHandlerMock = $this->getMockBuilder(ResponseHandlerInterface::class)
            ->getMock();


        $responseHandlerFactory = $this->getResponseFactory($this->responseHandlerMock);


        $this->application = new Application($this->slimAppMock, $responseHandlerFactory);
    }

    private function getResponseFactory(ResponseHandlerInterface $responseHandler): ResponseHandlerFactoryInterface
    {

        return new class($responseHandler) implements ResponseHandlerFactoryInterface
        {
            public $responseHandler;

            public function __construct(ResponseHandlerInterface $responseHandler)
            {
                $this->responseHandler = $responseHandler;
            }

            public function create(Operation $operation): ResponseHandlerInterface
            {
                return $this->responseHandler;
            }
        };
    }
}