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
        if (!class_exists($constraint->dto)) {
            throw new DataTransferNotFoundException($constraint->dto);
        }

        if (null !== $constraint->object && !class_exists($constraint->object)) {
            throw new RuntimeException(sprintf('Class %s not found. For DataTransfer constraint "NestedDataTransferConstraint"', $constraint->object));
        }

        if (!class_exists($constraint->collector)) {
            throw new ConstraintNotFoundException($constraint->collector);
        }

        $dataTransferControl->setIsIgnoreSetterCall(true);

        $currentDto = $dataTransferControl->dataTransfer;
        $allowNestedDto = true;

        if (null !== $constraint->thenCallback) {
            $allowNestedDto = $currentDto->{$constraint->thenCallback}($dataTransferControl->getDataValue());
        }

        if ($allowNestedDto) {
            $collector = $this->createCollector($constraint);
            $nestedDto = $this->createDTO($constraint, $collector, $dataTransferControl);

            $dataTransferControl->setDataTransferValue($nestedDto);

            $currentDto->addDataTransferCollection($constraint->dto, $nestedDto->getListDataTransferCollection());
        } else {
            $dataTransferControl->setDataTransferValue($dataTransferControl->property->getDefaultValue());
        }
    }

    private function createCollector(NestedDTO $constraint): CollectorInterface
    {
        $collector = new ($constraint->collector)();

        if (!$collector instanceof CollectorInterface) {
            throw new LogicException(sprintf('Collector %s does not implement the %s interface', $constraint->collector, ConstraintInterface::class));
        }

        return $collector;
    }

    private function createDTO(NestedDTO $constraint, CollectorInterface $collector, DataTransferControl $dataTransferControl): DataTransferInterface
    {
        $currentDto = $dataTransferControl->dataTransfer;
        $nestedDto = new ($constraint->dto)($collector, $currentDto->getReflectorManager(), $currentDto->getConstraintHandlerRegister());

        if (null !== $constraint->object) {
            $nestedDto->setObject(new ($constraint->object)());
        }

        $nestedDto->collect(is_array($dataTransferControl->getDataValue()) ? $dataTransferControl->getDataValue() : []);

        if (null !== $constraint->object) {
            $dataTransferControl->setObjectValue($nestedDto->getObject());
        }

        return $nestedDto;
    }
}