<?php
declare(strict_types=1);

namespace ApiMockery\Config\Swagger;

use ApiMockery\Api\ConfigParserInterface;
use ApiMockery\Dto\Operation;
use ApiMockery\Dto\Path;
use ApiMockery\Exception\ConfigurationMismatchException;
use Noodlehaus\ConfigInterface;

class V2 implements ConfigParserInterface
{
    const X_APIS_JSON = 'x-apis-json';

    const OPERATION_ID = 'operationId';

    const PRODUCES = 'produces';

    const PATHS = 'paths';

    /**
     * @param ConfigInterface $config
     * @return array
     * @throws ConfigurationMismatchException
     */
    const RESPONSE_HANDLER_TYPE = 'response_handler_type';

    const RESPONSE_HANDLER = 'response_handler';

    /**
     * @inheritdoc
     */
    public function parse(ConfigInterface $config): array
    {
        $paths = $config->get(self::PATHS);

        $parsedPaths = [];
        foreach ($paths as $baseUrl => $operations) {
            $parsedPaths[] = new Path($baseUrl, $this->parseOperations($operations));
        }

        return $parsedPaths;
    }

    /**
     * @param  array $operations
     * @return array
     * @throws ConfigurationMismatchException
     */
    protected function parseOperations(array $operations): array
    {
        $parsedOperations = [];

        foreach ($operations as $operationType => $operationAttributes) {
            $operationId = $operationAttributes[self::OPERATION_ID];
            $produces = $operationAttributes[self::PRODUCES];

            list($type, $responseHandler) = $this->parseResponseHandlerConfig($operationAttributes);

            $parsedOperations[] = new Operation($operationType, $operationId, $produces, $type, $responseHandler);
        }

        return $parsedOperations;
    }

    /**
     * @param array $operationAttributes
     * @return array
     * @throws ConfigurationMismatchException
     */
    protected function parseResponseHandlerConfig(array $operationAttributes): array
    {
        $this->validateResponseHandlerConfig($operationAttributes);

        $responseHandlerConfig = $operationAttributes[self::X_APIS_JSON];
        $type = $responseHandlerConfig[self::RESPONSE_HANDLER_TYPE];
        $responseHandler = $responseHandlerConfig[self::RESPONSE_HANDLER];

        return [$type, $responseHandler];
    }

    /**
     * @param array $operationAttributes
     * @throws ConfigurationMismatchException
     */
    private function validateResponseHandlerConfig(array $operationAttributes) : void
    {
        if (isset($operationAttributes[self::X_APIS_JSON]) === false) {
            throw new ConfigurationMismatchException('X_APIS_JSON attribute is missing.');
        }

        $config = $operationAttributes[self::X_APIS_JSON];

        if (isset($config[self::RESPONSE_HANDLER_TYPE]) === false) {
            throw new ConfigurationMismatchException('RESPONSE_HANDLER_TYPE attribute is missing.');
        }

        if (isset($config[self::RESPONSE_HANDLER]) === false) {
            throw new ConfigurationMismatchException('RESPONSE_HANDLER attribute is missing.');
        }
    }
}
