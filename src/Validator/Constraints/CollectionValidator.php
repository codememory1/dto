<?php

namespace Codememory\Dto\Validator\Constraints;

use Codememory\Dto\AbstractDataTransferObject;
use Codememory\Reflection\Reflectors\ClassReflector;
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

        foreach ($object->{$constraint->methodWithCollection}() as $key => $options) {
            /** @var ClassReflector $classReflector */
            $classReflector = $options['dto']->getClassReflector();
            $propertyName = explode('@', $key, 2)[1];
            $propertyReflector = $classReflector->getPropertiesIncludingParent([AbstractDataTransferObject::class])[$propertyName];
            $propertyValue = $propertyReflector->getValue($options['dto']);

            $this->context
                ->getValidator()
                ->inContext($this->context)
                ->atPath($key)
                ->validate($propertyValue, $options['constraints']);
        }
    }
}