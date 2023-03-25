<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ExpectArray implements ConstraintInterface
{
    public function __construct(
        public readonly array $expectKeys
    ) {
    }

    public function getHandler(): string
    {
        return ExpectArrayHandler::class;
    }
}