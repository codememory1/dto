<?php

namespace Codememory\Dto\Decorators\Property;

use Attribute;
use Codememory\Dto\Interfaces\MutatingDecoratorTypeInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class ToEnum implements PropertyDecoratorInterface
{
    public function __construct(
        public ?string $enum = null,
        public bool $value = false,
        public bool $multiple = false
    ) {
    }

    public function getType(): string
    {
        return MutatingDecoratorTypeInterface::class;
    }

    public function getHandler(): string
    {
        return ToEnumHandler::class;
    }
}
