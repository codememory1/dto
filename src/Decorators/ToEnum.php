<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ValueModifyingDecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class ToEnum implements DecoratorInterface, ValueModifyingDecoratorInterface
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
