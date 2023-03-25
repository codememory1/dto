<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
final class XSS implements ConstraintInterface
{
    public function getHandler(): string
    {
        return XSSHandler::class;
    }
}