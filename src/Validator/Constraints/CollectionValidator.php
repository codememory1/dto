<?php

namespace Codememory\Dto\Validator\Constraints;

use Codememory\Dto\DataTransferCollection;
use Codememory\Dto\Interfaces\DataTransferInterface;
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

        $dataTransferListCollection = $object->{$constraint->methodWithCollection}();

        if (!is_array($dataTransferListCollection)) {
            throw new LogicException(sprintf('The %s method in the %s class must return an array', $constraint->methodWithCollection, $object::class));
        }

        /** @var DataTransferCollection $collection */
        foreach ($dataTransferListCollection as $collection) {
            foreach ($collection->getPropertyValidation() as $propertyName => $constraints) {
                $this->context
                    ->getValidator()
                    ->inContext($this->context)
                    ->atPath($this->generatePath($collection->getDataTransfer(), $propertyName))
                    ->validate($collection->getDataTransfer()->{$propertyName}, $constraints);
            }
        }
    }

    private function generatePath(DataTransferInterface $dataTransfer, string $propertyName): string
    {
        return sprintf('%s_%s', $dataTransfer::class, $propertyName);
    }
}