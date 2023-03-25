<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use function is_array;

final class ExpectMultiArrayHandler implements ConstraintHandlerInterface
{
    /**
     * @param ExpectMultiArray $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $newArray = [];

        foreach ($dataTransferControl->getDataTransferValue() as $index => $item) {
            if (is_array($item)) {
                $newItem = [];

                foreach ($constraint->expectKeys as $expectKey) {
                    if (array_key_exists($expectKey, $item)) {
                        $newItem[$expectKey] = $item[$expectKey];
                    }
                }

                if ($constraint->itemKeyAsNumber) {
                    $newArray[] = $newItem;
                } else {
                    $newArray[$index] = $newItem;
                }
            }
        }

        $dataTransferControl->setValue($newArray);
    }
}