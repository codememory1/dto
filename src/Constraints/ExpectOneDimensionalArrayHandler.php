<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;

final class ExpectOneDimensionalArrayHandler implements ConstraintHandlerInterface
{
    /**
     * @param ExpectOneDimensionalArray $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $values = [];

        foreach ($dataTransferControl->getDataTransferValue() as $value) {
            $valueType = gettype($value);

            if (!is_array($value) && ([] === $constraint->types || in_array($valueType, $constraint->types))) {
                $values[] = $value;
            }
        }

        $dataTransferControl->setValue($values);
    }
}