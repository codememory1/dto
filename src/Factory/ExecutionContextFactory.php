<?php

namespace Codememory\Dto\Factory;

use Codememory\Dto\Context\ExecutionContext;
use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Dto\Interfaces\ExecutionContextFactoryInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Reflection\Reflectors\PropertyReflector;
use function Symfony\Component\String\u;

final class ExecutionContextFactory implements ExecutionContextFactoryInterface
{
    public function createExecutionContext(DataTransferObjectInterface $dataTransferObject, PropertyReflector $property, array $data): ExecutionContextInterface
    {
        $configuration = $dataTransferObject->getConfiguration();
        $context = new ExecutionContext($dataTransferObject, $property, $data);
        $dataKey = $configuration->getDataKeyNamingStrategy()->convert($property->getName());

        $context->setDataValue($context->getData()[$dataKey] ?? $property->getDefaultValue());
        $context->setDataTransferObjectValue($context->getDataValue());
        $context->setValueForHarvestableObject($context->getDataTransferObjectValue());
        $context->setDataKey($dataKey);
        $context->setSkipThisProperty(false);
        $context->setIgnoredSetterCallForHarvestableObject(false);
        $context->setNameSetterMethodForHarvestableObject(u("set_{$property->getName()}")->camel());

        return $context;
    }
}