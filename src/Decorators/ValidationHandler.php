<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Collection\DataTransferObjectPropertyConstraintsCollection;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Dto\ValueObject\DataTransferObjectPropertyConstraints;

final class ValidationHandler implements DecoratorHandlerInterface
{
    /**
     * @param Validation $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $collection = $context->getDataTransferObject()->getDataTransferObjectPropertyConstrainsCollection($context->getDataTransferObject()::class);

        if (null === $collection) {
            $collection = new DataTransferObjectPropertyConstraintsCollection($context->getDataTransferObject(), [
                new DataTransferObjectPropertyConstraints($context->getProperty()->getName(), $decorator->assert)
            ]);

            $context->getDataTransferObject()->addDataTransferObjectPropertyConstraintsCollection($context->getDataTransferObject(), $collection);
        } else {
            $collection->addDataTransferObjectPropertyConstraints($context->getProperty()->getName(), $decorator->assert);
        }
    }
}