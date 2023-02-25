<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ToEntityConstraintHandler implements ConstraintHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    /**
     * @param ToEntityConstraint $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $dataTransfer = $dataTransferControl->dataTransfer;
        $repository = $this->em->getRepository($constraint->entity ?: $dataTransferControl->property->getType()->getName());
        $dataValue = $dataTransferControl->getDataValue();

        if (null === $constraint->whereCallback) {
            $entity = $repository->findOneBy([$constraint->byKey => $dataValue]);
        } else {
            $where = $dataTransfer->{$constraint->whereCallback}($dataValue, $dataTransferControl);
            $entity = $repository->findOneBy($where);
        }

        if (null !== $constraint->entityNotFoundCallback && null === $entity) {
            $dataTransfer->{$constraint->entityNotFoundCallback}($dataValue, $dataTransferControl);
        }

        $dataTransferControl->setValue($entity);
    }
}