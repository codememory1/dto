<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\Reflectors\PropertyReflector;

interface ExecutionContextFactoryInterface
{
    public function createExecutionContext(DataTransferObjectInterface $dataTransferObject, PropertyReflector $property, array $data): ExecutionContextInterface;
}