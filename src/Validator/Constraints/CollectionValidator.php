<?php

namespace Codememory\Dto\Validator\Constraints;

use Codememory\Dto\Collection\DataTransferObjectPropertyConstraintsCollection;
use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use function is_array;
use LogicException;
use RuntimeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class CollectionValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Collection) {
            throw new UnexpectedTypeException($constraint, Collection::class);
        }

        $object = $this->context->getObject();

        if (!method_exists($object, $constraint->methodWithCollection)) {
            throw new RuntimeException(sprintf('The %s method in the %s class does not exist', $constraint->methodWithCollection, $object::class));
        }

        $listCollections = $object->{$constraint->methodWithCollection}();

        if (!is_array($listCollections)) {
            throw new LogicException(sprintf('The %s method in the %s class must return an array', $constraint->methodWithCollection, $object::class));
        }

        /** @var DataTransferObjectPropertyConstraintsCollection $collection */
        foreach ($listCollections as $collection) {
            if ($collection instanceof DataTransferObjectPropertyConstraintsCollection) {
                foreach ($collection->getListDataTransferObjectPropertyConstraints() as $dataTransferObjectPropertyConstraint) {
                    $propertyName = $dataTransferObjectPropertyConstraint->getPropertyName();
                    $propertyReflector = $collection->getDataTransfer()->getClassReflector()->getPropertyByName($propertyName);
                    $propertyValue = $propertyReflector->getValue($collection->getDataTransfer());

                    $this->context
                        ->getValidator()
                        ->inContext($this->context)
                        ->atPath($this->generatePath($collection->getDataTransfer(), $propertyName))
                        ->validate($propertyValue, $dataTransferObjectPropertyConstraint->getConstraints());
                }
            }
        }
    }

    private function generatePath(DataTransferObjectInterface $dataTransfer, string $propertyName): string
    {
        return sprintf('%s_%s', $dataTransfer::class, $propertyName);
    }
}