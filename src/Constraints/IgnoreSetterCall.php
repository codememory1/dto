<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class IgnoreSetterCall implements ConstraintInterface
{
    public function getHandler(): string
    {
        return IgnoreSetterCallHandler::class;
    }
}