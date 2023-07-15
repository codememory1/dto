<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
final class XSS implements DecoratorInterface
{
    public function getHandler(): string
    {
        return XSSHandler::class;
    }
}