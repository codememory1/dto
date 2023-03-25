<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class AsPatch implements ConstraintInterface
{
    public function __construct(
        public readonly array $assert = []
    ) {
    }

    public function getHandler(): string
    {
        return AsPatchHandler::class;
    }
}