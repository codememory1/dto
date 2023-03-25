<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ToEnum implements ConstraintInterface
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
