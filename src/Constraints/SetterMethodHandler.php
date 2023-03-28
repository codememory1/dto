<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;

final class SetterMethodHandler implements ConstraintHandlerInterface
{
    /**
     * @param SetterMethod $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $dataTransferControl->setSetterMethodNameToObject($constraint->name);
    }
}