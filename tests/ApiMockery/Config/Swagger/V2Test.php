<?php
/**
 * Created by PhpStorm.
 * User: roettigl
 * Date: 08.06.18
 * Time: 17:27
 */

namespace ApiMockery\Tests\Config\Swagger;

use ApiMockery\Config\Swagger\V2;
use ApiMockery\Dto\Path;
use ApiMockery\Exception\ConfigurationMismatchException;
use Noodlehaus\ConfigInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class V2Test
 * @package ApiMockery\Tests\Config\Swagger
 * @cover ApiMockery\Config\Swagger\V2
 */
class V2Test extends TestCase
{
    /**
     * @var V2
     */
    private $v2ConfigParser;

    /**
     * @var ConfigInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $configMock;

    /**
     * @return void
     */
    public function testParseEmptyConfig()
    {

        $this->configMock->expects($this->once())
            ->method('get')
            ->with('paths')
            ->willReturn([]);

        $result = $this->v2ConfigParser->parse($this->configMock);
        self::assertEquals([], $result);
    }

    /**
     * @expectedException ApiMockery\Exception\ConfigurationMismatchException
     * @expectedExceptionMessage X_APIS_JSON attribute is missing
     */
    public function testConfigurationMismatchException()
    {
        $this->configMock->expects($this->once())
            ->method('get')
            ->with('paths')
            ->willReturn(array(
                '/pets' =>
                    array(
                        'get' =>
                            array(
                                'description' => 'Returns all pets from the system that the user has access to',
                                'operationId' => 'findPets',
                                'produces' =>
                                    array(
                                        0 => 'application/xml',
                                    )
                            )

                    )
            ));

        $this->v2ConfigParser->parse($this->configMock);
    }


    public function testParseOneEndPoint()
    {
        $this->configMock->expects($this->once())
            ->method('get')
            ->with('paths')
            ->willReturn(array(
                '/pets' =>
                    array(
                        'get' =>
                            array(
                                'description' => 'Returns all pets from the system that the user has access to',
                                'operationId' => 'findPets',
                                'produces' =>
                                    array(
                                        0 => 'application/xml',
                                    ),
                                'x-apis-json' =>
                                    array(
                                        'response_handler_type' => 'object',
                                        'response_handler' => '\\Pets\\GetListService',
                                    ),
                            )
                    )
            ));

        $parsedPaths = $this->v2ConfigParser->parse($this->configMock);
        self::assertCount(1, $parsedPaths);

        /**
         * @var Path $path
         */
        foreach ($parsedPaths as $path) {
            self::assertEquals($path->getBaseUrl(), '/pets');

            $operations = $path->getOperations();
            self::assertCount(1, $path->getOperations());

            self::assertCount(1, $operations[0]->getProduces());
            self::assertEquals('get', $operations[0]->getType());
            self::assertEquals('findPets', $operations[0]->getOperationId());
            self::assertEquals('object', $operations[0]->getResponseHandlerType());
            self::assertEquals('\\Pets\\GetListService', $operations[0]->getResponseHandler());
        }
    }

    /**
     *
     */
    protected function setUp()
    {
        $this->v2ConfigParser = new V2();
        $this->configMock = $this->getMockBuilder(ConfigInterface::class)->getMock();
    }
}
