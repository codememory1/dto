<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class SetterMethod implements ConstraintInterface
{
    public function __construct(
        public readonly string $name
    ) {
    }

    public function getHandler(): string
    {
        return SetterMethodHandler::class;
    }
}