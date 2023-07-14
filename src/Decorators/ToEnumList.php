<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ToEnumList implements DecoratorInterface
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
