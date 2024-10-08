<?php

namespace Codememory\Dto\Factory;

use Codememory\Dto\Context\ExecutionContext;
use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Dto\Interfaces\DataTransferObjectManagerInterface;
use Codememory\Dto\Interfaces\ExecutionContextFactoryInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Reflection\Reflectors\PropertyReflector;
use function Symfony\Component\String\u;

final class ExecutionContextFactory implements ExecutionContextFactoryInterface
{
    public function createExecutionContext(
        DataTransferObjectManagerInterface $manager,
        DataTransferObjectInterface $dataTransferObject,
        PropertyReflector $property,
        array $inputData
    ): ExecutionContextInterface {
        $context = new ExecutionContext($manager, $dataTransferObject, $property, $inputData);
        $snakePropertyName = u($property->getName())->snake()->toString();
        $value = $property->getDefaultValue();

        if (array_key_exists($snakePropertyName, $context->getInputData())) {
            $value = $context->getInputData()[$snakePropertyName];
        } else if (array_key_exists($property->getName(), $inputData)) {
            $value = $context->getInputData()[$property->getName()];
        }
        
        $context->setValue($value);
        $context->setSkipThisProperty(false);

        return $context;
    }
}