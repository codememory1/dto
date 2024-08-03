<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ValueModifyingDecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class ToEnumList implements DecoratorInterface, ValueModifyingDecoratorInterface
{
    public function __construct(
        public readonly string $enum,
        public readonly bool $byValue = false,
        public readonly bool $unique = true
    ) {
    }

    public function getHandler(): string
    {
        return ToEnumListHandler::class;
    }
}
