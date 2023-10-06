<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use function is_array;

final class ExpectMultiArrayHandler implements DecoratorHandlerInterface
{
    /**
     * @param ExpectMultiArray $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $values = [];

        if (is_array($context->getDataTransferObjectValue())) {
            foreach ($context->getDataTransferObjectValue() as $index => $item) {
                if (is_array($item)) {
                    $newItem = [];

                    foreach ($decorator->expectKeys as $expectKey) {
                        if (array_key_exists($expectKey, $item)) {
                            $newItem[$expectKey] = $item[$expectKey];
                        }
                    }

                    $decorator->itemKeyAsNumber ? $values[] = $newItem : $values[$index] = $newItem;
                }
            }
        }

        $context->setDataTransferObjectValue($values);
        $context->setValueForHarvestableObject($values);
    }
}