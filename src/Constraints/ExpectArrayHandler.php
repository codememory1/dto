<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;

final class ExpectArrayHandler implements ConstraintHandlerInterface
{
    /**
     * @param ExpectArray $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $array = $dataTransferControl->getDataTransferValue();
        $newArray = [];

        foreach ($constraint->expectKeys as $expectKey) {
            if (array_key_exists($expectKey, $array)) {
                $newArray[$expectKey] = $array[$expectKey];
            }
        }

        $dataTransferControl->setValue($newArray);
    }
}