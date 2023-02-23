<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;

final class CallbackConstraintHandler implements ConstraintHandlerInterface
{
    /**
     * @param CallbackConstraint $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $dataTransferControl->dataTransfer->{$constraint->methodName}($dataTransferControl);
    }
}