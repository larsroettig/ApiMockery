<?php
/**
 * Created by PhpStorm.
 * User: roettigl
 * Date: 24.06.18
 * Time: 15:33
 */

namespace ApiMockery\Api;


use ApiMockery\Dto\Operation;
use ApiMockery\Exception\ConfigurationMismatchException;

interface ResponseHandlerFactoryInterface
{

    /**
     * @param Operation $operation
     * @return ResponseHandlerInterface
     * @throws ConfigurationMismatchException
     */
    public function create(Operation $operation): ResponseHandlerInterface;
}
