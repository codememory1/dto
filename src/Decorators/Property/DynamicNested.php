<?php

namespace Codememory\Dto\Decorators\Property;

use Attribute;
use Codememory\Dto\Interfaces\MutatingDecoratorTypeInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class DynamicNested implements PropertyDecoratorInterface
{
    public function __construct(
        public readonly string|array $callback
    ) {
    }

    public function getHandler(): string
    {
        return DynamicNestedHandler::class;
    }

    public function getType(): string
    {
        return MutatingDecoratorTypeInterface::class;
    }
}