<?php

namespace Codememory\Dto\Decorators\Property;

use Attribute;
use Codememory\Dto\Interfaces\PropertyDecoratorInterface;
use Codememory\Dto\Interfaces\SymfonyValidationDecoratorTypeInterface;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class SymfonyValidation implements PropertyDecoratorInterface
{
    /**
     * @param array<int, Constraint> $constraints
     */
    public function __construct(
        public array $constraints
    ) {
    }

    public function getType(): string
    {
        return SymfonyValidationDecoratorTypeInterface::class;
    }

    public function getHandler(): string
    {
        return SymfonyValidationHandler::class;
    }
}