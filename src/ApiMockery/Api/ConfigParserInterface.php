<?php
declare(strict_types=1);

namespace ApiMockery\Api;

use ApiMockery\Dto\Path;
use ApiMockery\Exception\ConfigurationMismatchException;
use Noodlehaus\ConfigInterface;

/**
 * Interface ConfigParserInterface
 * @package ApiMockery\Api
 */
interface ConfigParserInterface
{
    /**
     * Parse the given configuration.
     *
     * @return Path[]
     * @throws ConfigurationMismatchException
     */
    public function parse(ConfigInterface $config): array;
}
