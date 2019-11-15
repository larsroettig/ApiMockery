<?php
declare(strict_types=1);

namespace ApiMockery;

use ApiMockery\Api\ConfigParserInterface;
use ApiMockery\Config\Swagger\V2;
use ApiMockery\Exception\ConfigurationMismatchException;
use Noodlehaus\ConfigInterface;

/**
 * Class ConfigFactory
 * @package ApiMockery
 */
class ConfigParserFactory
{
    /**
     * @var array
     */
    protected $configParser =
        [
            '2.0' => V2::class,
        ];

    /**
     * @throws ConfigurationMismatchException
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-suppress InvalidStringClass
     */
    public function create(ConfigInterface $config): ConfigParserInterface
    {
        $className = $this->detectConfigParserType($config);
        return new $className();
    }

    /**
     * @throws ConfigurationMismatchException
     */
    protected function detectConfigParserType(ConfigInterface $config): string
    {
        $swaggerVersion = $config->get('swagger', 'not_exists');

        if (array_key_exists($swaggerVersion, $this->configParser) === false) {
            throw new ConfigurationMismatchException('Can not find config parser version');
        }

        return $this->configParser[$swaggerVersion];
    }
}
