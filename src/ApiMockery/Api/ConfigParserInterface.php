<?php
declare(strict_types=1);

namespace ApiMockery\Api;

use Noodlehaus\ConfigInterface;

/**
 * Interface ConfigParserInterface
 * @package ApiMockery\Api
 */
interface ConfigParserInterface
{
    public function parse(ConfigInterface $config): array;
}
