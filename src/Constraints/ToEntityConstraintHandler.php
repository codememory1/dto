<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use LogicException;

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
        $repository = $this->em->getRepository($dataTransferControl->property->getType()->getName());

        if (!$constraint->isList) {
            $entity = $repository->findOneBy([$constraint->byKey => $dataTransferControl->getDataValue()]);

            if (null === $entity) {
                $this->notFoundEntityHandler($constraint, $dataTransferControl, $dataTransferControl->getDataValue());
            } else {
                $dataTransferControl->setValue($entity);
            }
        } else {
            $this->listHandler($constraint, $dataTransferControl, $repository);
        }
    }

    /**
     * @param ToEntityConstraint $constraint
     */
    private function listHandler(ConstraintInterface $constraint, DataTransferControl $dataTransferControl, ObjectRepository $repository): void
    {
        $values = array_map(static function (mixed $value) use ($constraint, $dataTransferControl) {
            if (null !== $constraint->itemValueConverter) {
                return $dataTransferControl->dataTransfer->{$constraint->itemValueConverter}($value);
            }

            return $value;
        }, $dataTransferControl->getDataValue());
        $values = $constraint->uniqueInList ? array_unique($values) : $values;

        foreach ($values as &$value) {
            $entity = $repository->findOneBy([$constraint->byKey => $value]);

            if (null === $entity) {
                $this->notFoundEntityHandler($constraint, $dataTransferControl, $value);
            } else {
                $value = $entity;
            }
        }

        $dataTransferControl->setValue($values);
    }

    /**
     * @param ToEntityConstraint $constraint
     */
    private function notFoundEntityHandler(ConstraintInterface $constraint, DataTransferControl $dataTransferControl, mixed $value): void
    {
        if ($constraint->checkNotFoundEntity) {
            if (null === $constraint->customHandlerNotFoundEntity) {
                throw new LogicException(sprintf('Entity %s by key %s with value %s not found', $dataTransferControl->property->getType()->getName(), $constraint->byKey, $value));
            }
            $dataTransferControl->dataTransfer->{$constraint->customHandlerNotFoundEntity}($value, $dataTransferControl);
        }
    }
}