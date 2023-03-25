<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferCollection;
use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;

final class ValidationHandler implements ConstraintHandlerInterface
{
    /**
     * @param Validation $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        /** @var DataTransferCollection $collection */
        $collection = $dataTransferControl->dataTransfer->getListDataTransferCollection()[$dataTransferControl->dataTransfer::class];

        $collection->addPropertyValidation($dataTransferControl->property->getName(), $constraint->assert);
    }
}