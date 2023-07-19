<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Exceptions\DataTransferObjectNotFoundException;
use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use function is_array;
use RuntimeException;

final class NestedDTOHandler implements DecoratorHandlerInterface
{
    /**
     * @param NestedDTO $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        if (!class_exists($decorator->dto)) {
            throw new DataTransferObjectNotFoundException($decorator->dto);
        }

        if (null !== $decorator->object && 'current' !== $decorator->object && !class_exists($decorator->object)) {
            throw new RuntimeException(sprintf('Class %s not found. For DataTransfer constraint "NestedDataTransferConstraint"', $decorator->object));
        }

        $context->setIgnoredSetterCallForHarvestableObject(true);

        $currentDto = $context->getDataTransferObject();
        $allowNestedDto = true;

        if (null !== $decorator->thenCallback) {
            $allowNestedDto = $currentDto->{$decorator->thenCallback}($context->getDataValue());
        }

        if ($allowNestedDto && is_array($context->getDataValue())) {
            $nestedDto = $this->createDTO($decorator, $context);

            $context->setDataTransferObjectValue($nestedDto);

            $currentDto->addDataTransferObjectPropertyConstraintsCollection($nestedDto, $nestedDto->getListDataTransferObjectPropertyConstrainsCollection());
        } else {
            $context->setDataTransferObjectValue($context->getProperty()->getDefaultValue());
        }
    }

    private function createDTO(NestedDTO $decorator, ExecutionContextInterface $context): DataTransferObjectInterface
    {
        $currentDto = $context->getDataTransferObject();
        $nestedDto = new ($decorator->dto)(
            $currentDto->getCollector(),
            $currentDto->getConfigurationFactory(),
            $currentDto->getExecutionContextFactory(),
            $currentDto->getDecoratorHandlerRegistrar(),
            $currentDto->getReflectorManager()
        );

        if (null !== $decorator->object) {
            $object = 'current' === $decorator->object ? $context->getDataTransferObject()->getHarvestableObject() : new ($decorator->object)();

            $nestedDto->setObject($object);
        }

        $nestedDto->collect(is_array($context->getDataValue()) ? $context->getDataValue() : []);

        if (null !== $decorator->object) {
            $context->setValueForHarvestableObject($nestedDto->getObject());
        }

        return $nestedDto;
    }
}