<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ToEntityListConstraintHandler implements ConstraintHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    /**
     * @param ToEntityListConstraint $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $dataTransfer = $dataTransferControl->dataTransfer;
        $repository = $this->em->getRepository($constraint->entity ?: $dataTransferControl->property->getType()->getName());
        $values = $this->convertIterationValue($constraint, $dataTransferControl);
        $values = $constraint->unique ? array_unique($values) : $values;

        foreach ($values as &$value) {
            if (null !== $constraint->byKey) {
                $entity = $repository->findOneBy([$constraint->byKey => $value]);
            } else {
                $where = $dataTransfer->{$constraint->whereCallback}($value, $dataTransferControl);
                $entity = $repository->findOneBy($where);
            }

            if (null !== $constraint->entityNotFoundCallback && null === $entity) {
                $dataTransfer->{$constraint->entityNotFoundCallback}($value, $dataTransferControl);
            }
        }

        $dataTransferControl->setValue($values);
    }

    /**
     * @param ToEntityListConstraint $constraint
     */
    private function convertIterationValue(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): array
    {
        return array_map(static function(mixed $value) use ($constraint, $dataTransferControl) {
            if (null !== $constraint->valueConverterCallback) {
                return $dataTransferControl->dataTransfer->{$constraint->valueConverterCallback}($value);
            }

            return $value;
        }, $dataTransferControl->getDataValue());
    }
}