<?php

declare (strict_types = 1);

namespace Codememory\Dto\Decorators\Property;

use Attribute;
use Codememory\Dto\Interfaces\PropertyDecoratorInterface;
use Codememory\Dto\Interfaces\SymfonyValidationDecoratorTypeInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
readonly class DynamicSymfonyValidation implements PropertyDecoratorInterface
{
    public function __construct(
        public string|array $callback
    ) {
    }

    public function getHandler(): string
    {
        return DynamicSymfonyValidationHandler::class;
    }

    public function getType(): string
    {
        return SymfonyValidationDecoratorTypeInterface::class;
    }
}