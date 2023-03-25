<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ExpectMultiArray implements ConstraintInterface
{
    public function __construct(
        public readonly array $expectKeys,
        public readonly bool $itemKeyAsNumber = true
    ) {
    }

    public function getHandler(): string
    {
        return ExpectMultiArrayHandler::class;
    }
}