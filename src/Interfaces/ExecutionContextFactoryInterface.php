<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\Reflectors\PropertyReflector;

interface ExecutionContextFactoryInterface
{
    public function createExecutionContext(
        DataTransferObjectManagerInterface $manager,
        DataTransferObjectInterface $dataTransferObject,
        PropertyReflector $property,
        array $inputData
    ): ExecutionContextInterface;
}