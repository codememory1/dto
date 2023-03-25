<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use function constant;
use function defined;

final class ToEnumHandler implements ConstraintHandlerInterface
{
    /**
     * @param ToEnum $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $enum = $dataTransferControl->property->getType()->getName();

        $dataTransferControl->setValue(null);

        if (class_exists($enum)) {
            if (!$constraint->byValue) {
                $casePath = "{$enum}::{$dataTransferControl->getDataValue()}";

                if (defined($casePath)) {
                    $dataTransferControl->setValue(constant($casePath));
                }
            } else {
                $dataTransferControl->setValue($enum::tryFrom($dataTransferControl->getDataValue()));
            }
        }
    }
}