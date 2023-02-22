<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class IgnoreSetterCallConstraint implements ConstraintInterface
{
    public function getHandler(): string
    {
        return IgnoreSetterCallConstraintHandler::class;
    }
}