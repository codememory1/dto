<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Exceptions\MethodNotFoundException;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ToEntityHandler implements ConstraintHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    /**
     * @param ToEntity $constraint
     *
     * @throws MethodNotFoundException
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $dataTransfer = $dataTransferControl->dataTransfer;
        $repository = $this->em->getRepository($constraint->entity ?: $dataTransferControl->property->getType()->getName());
        $dataValue = $dataTransferControl->getDataValue();

        $this->throwIfMethodsNotFound($constraint, $dataTransferControl);

        if (null === $constraint->whereCallback) {
            $entity = $repository->findOneBy([$constraint->byKey => $dataValue]);
        } else {
            $entity = $dataTransfer->{$constraint->whereCallback}($dataValue, $repository, $dataTransferControl);
        }

        if (null !== $constraint->entityNotFoundCallback && null === $entity) {
            $dataTransfer->{$constraint->entityNotFoundCallback}($dataValue, $dataTransferControl);
        }

        $dataTransferControl->setValue($entity);
    }

    /**
     * @param ToEntity $constraint
     *
     * @throws MethodNotFoundException
     */
    private function throwIfMethodsNotFound(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $dataTransfer = $dataTransferControl->dataTransfer;

        if (null !== $constraint->whereCallback && !method_exists($dataTransfer, $constraint->whereCallback)) {
            throw new MethodNotFoundException($dataTransfer::class, $constraint->whereCallback);
        }

        if (null !== $constraint->entityNotFoundCallback && !method_exists($dataTransfer, $constraint->entityNotFoundCallback)) {
            throw new MethodNotFoundException($dataTransfer::class, $constraint->entityNotFoundCallback);
        }
    }
}