<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Exceptions\MethodNotFoundException;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ToEntityListHandler implements ConstraintHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    /**
     * @param ToEntityList $constraint
     *
     * @throws MethodNotFoundException
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $dataTransfer = $dataTransferControl->dataTransfer;
        $repository = $this->em->getRepository($constraint->entity ?: $dataTransferControl->property->getType()->getName());
        $values = $this->convertIterationValue($constraint, $dataTransferControl);
        $values = $constraint->unique ? array_unique($values) : $values;

        $this->throwIfMethodsNotFound($constraint, $dataTransferControl);

        foreach ($values as &$value) {
            if (null !== $constraint->byKey) {
                $entity = $repository->findOneBy([$constraint->byKey => $value]);
            } else {
                $entity = $dataTransfer->{$constraint->whereCallback}($value, $repository, $dataTransferControl);
            }

            if (null !== $constraint->entityNotFoundCallback && null === $entity) {
                $dataTransfer->{$constraint->entityNotFoundCallback}($value, $dataTransferControl);
            } else {
                $value = $entity;
            }
        }

        $dataTransferControl->setValue($values);
    }

    /**
     * @param ToEntityList $constraint
     *
     * @throws MethodNotFoundException
     */
    private function throwIfMethodsNotFound(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $dataTransfer = $dataTransferControl->dataTransfer;

        if (null !== $constraint->entityNotFoundCallback && !method_exists($dataTransfer, $constraint->entityNotFoundCallback)) {
            throw new MethodNotFoundException($dataTransfer::class, $constraint->entityNotFoundCallback);
        }

        if (null !== $constraint->whereCallback) {
            throw new MethodNotFoundException($dataTransfer::class, $constraint->whereCallback);
        }
    }

    /**
     * @param ToEntityList $constraint
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