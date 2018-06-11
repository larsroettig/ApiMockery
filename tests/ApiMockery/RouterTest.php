<?php

namespace ApiMockery\Tests;

use ApiMockery\Dto\Operation;
use ApiMockery\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    /**
     * @var \Slim\App | \PHPUnit_Framework_MockObject_MockObject
     */
    private $app;

    /**
     * @var Router
     */
    private $router;

    /**
     *
     */
    public function testSetup_OneRoute()
    {
        $this->markTestSkipped();
        $operationId = 'findPets';
        $produces =
            array(
                0 => 'application/json',
                1 => 'application/xml',
                2 => 'text/xml',
                3 => 'text/html',
            );
        $type = 'get';
        $responseHandlerType = 'object';
        $responseHandler = '\\Pets\\GetListService';

        $operation1 = new Operation($type, $operationId, $produces, $responseHandlerType, $responseHandler);
        $operations = [$operation1];
        $path1 = new \ApiMockery\Dto\Path('/pets', $operations);


        $this->router->setup([$path1]);
    }

    /**
     *
     */
    protected function setUp()
    {
        $this->app = $this->getMockBuilder(\Slim\App::class)->getMock();
        $this->router = new Router(__DIR__, $this->app);
    }
}
