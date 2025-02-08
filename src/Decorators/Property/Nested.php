<?php

namespace Codememory\Dto\Decorators\Property;

use Attribute;
use Codememory\Dto\Interfaces\MutatingDecoratorTypeInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
readonly class Nested implements PropertyDecoratorInterface
{
    public function __construct(
        public ?string $className = null
    ) {
    }

    public function getType(): string
    {
        return MutatingDecoratorTypeInterface::class;
    }

    public function getHandler(): string
    {
        return NestedHandler::class;
    }
}