<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;

final class CallbackHandler implements ConstraintHandlerInterface
{
    /**
     * @param callable $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $dataTransferControl->dataTransfer->{$constraint->methodName}($dataTransferControl);
    }
}