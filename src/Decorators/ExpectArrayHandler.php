<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;

final class ExpectArrayHandler implements DecoratorHandlerInterface
{
    /**
     * @param ExpectArray $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $values = [];

        if ($context->getDataTransferObjectValue()) {
            foreach ($decorator->expectKeys as $expectKey) {
                if (array_key_exists($expectKey, $context->getDataTransferObjectValue())) {
                    $values[$expectKey] = $context->getDataTransferObjectValue()[$expectKey];
                }
            }
        }

        $context->setDataTransferObjectValue($values);
        $context->setValueForHarvestableObject($values);
    }
}