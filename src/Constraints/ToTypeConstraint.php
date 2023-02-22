<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
final class ToTypeConstraint implements ConstraintInterface
{
    public function __construct(
        public readonly ?string $type = null,
        public readonly bool $onlyData = false
    ) {
    }

    public function getHandler(): string
    {
        return ToTypeConstraintHandler::class;
    }
}