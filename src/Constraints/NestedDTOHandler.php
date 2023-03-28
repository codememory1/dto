<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Exceptions\ConstraintNotFoundException;
use Codememory\Dto\Exceptions\DataTransferNotFoundException;
use Codememory\Dto\Interfaces\CollectorInterface;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Codememory\Dto\Interfaces\DataTransferInterface;
use function is_array;
use LogicException;
use RuntimeException;

final class NestedDTOHandler implements ConstraintHandlerInterface
{
    /**
     * @param NestedDTO $constraint
     *
     * @throws ConstraintNotFoundException
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        if (!class_exists($constraint->dataTransfer)) {
            throw new DataTransferNotFoundException($constraint->dataTransfer);
        }

        if (null !== $constraint->object && !class_exists($constraint->object)) {
            throw new RuntimeException(sprintf('Class %s not found. For DataTransfer constraint "NestedDataTransferConstraint"', $constraint->object));
        }

        if (!class_exists($constraint->collector)) {
            throw new ConstraintNotFoundException($constraint->collector);
        }

        $collector = new ($constraint->collector)();

        if (!$collector instanceof CollectorInterface) {
            throw new LogicException(sprintf('Collector %s does not implement the %s interface', $constraint->collector, ConstraintInterface::class));
        }

        /** @var DataTransferInterface $dataTransfer */
        $dataTransfer = new ($constraint->dataTransfer)($collector, $dataTransferControl->dataTransfer->getReflectorManager());

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