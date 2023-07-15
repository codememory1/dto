<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ToEnum implements DecoratorInterface
{
    public function __construct(
        public readonly bool $byValue = false
    ) {
    }

    public function getHandler(): string
    {
        return ToEnumHandler::class;
    }
}
