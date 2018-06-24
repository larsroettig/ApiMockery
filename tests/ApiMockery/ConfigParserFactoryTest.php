<?php

namespace ApiMockery\Tests;

use ApiMockery\ConfigParserFactory;
use Noodlehaus\ConfigInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigParserFactoryTest
 * @package ApiMockery\Tests
 * @cover ConfigParserFactory
 */
class ConfigParserFactoryTest extends TestCase
{
    /**
     * @var ConfigParserFactory
     */
    private $configParserFactory;

    /**
     * @var ConfigInterface| \PHPUnit_Framework_MockObject_MockObject
     */
    private $configMock;

    /**
     * @expectedException  \ApiMockery\Exception\ConfigurationMismatchException
     */
    public function testParseEmptyConfig()
    {
        $this->configMock->expects($this->once())
            ->method('get')
            ->willReturn('not_exists');

        $result = $this->configParserFactory->create($this->configMock);
    }


    public function testParserSuceessfuly()
    {
        $this->configMock->expects($this->once())
            ->method('get')
            ->willReturn('2.0');

        $result = $this->configParserFactory->create($this->configMock);
        self::assertInstanceOf(\ApiMockery\Api\ConfigParserInterface::class, $result);
    }

    /**
     *
     */
    protected function setUp()
    {
        $this->configParserFactory = new ConfigParserFactory();
        $this->configMock = $this->getMockBuilder(ConfigInterface::class)->getMock();
    }
}
