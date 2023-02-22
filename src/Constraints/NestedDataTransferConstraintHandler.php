<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\Collectors\BaseCollector;
use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Exceptions\DataTransferNotFoundException;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Codememory\Dto\Interfaces\DataTransferInterface;
use function is_array;
use RuntimeException;

final class NestedDataTransferConstraintHandler implements ConstraintHandlerInterface
{
    /**
     * @param NestedDataTransferConstraint $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        if (!class_exists($constraint->dataTransfer)) {
            throw new DataTransferNotFoundException($constraint->dataTransfer);
        }

        if (null !== $constraint->object && !class_exists($constraint->object)) {
            throw new RuntimeException(sprintf('Class %s not found. For DataTransfer constraint "NestedDataTransferConstraint"', $constraint->object));
        }

        /** @var DataTransferInterface $dataTransfer */
        $dataTransfer = new ($constraint->dataTransfer)(new BaseCollector());

        if (null !== $constraint->object) {
            $dataTransfer->setObject(new ($constraint->object)());
        } else {
            $dataTransferControl->setIsIgnoreSetterCall(true);
        }

        $dataTransfer->collect(is_array($dataTransferControl->getDataValue()) ? $dataTransferControl->getDataValue() : []);

        if (null !== $constraint->object) {
            $dataTransferControl->setObjectValue($dataTransfer->getObject());
        }

        $dataTransferControl->setDataTransferValue($dataTransfer);
        $dataTransferControl->dataTransfer->addDataTransferCollection(
            $constraint->dataTransfer,
            $dataTransfer->getListDataTransferCollection()
        );
    }
}