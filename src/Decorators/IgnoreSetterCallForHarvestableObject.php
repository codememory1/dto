<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class IgnoreSetterCallForHarvestableObject implements DecoratorInterface
{
    public function getHandler(): string
    {
        return IgnoreSetterCallForHarvestableObjectHandler::class;
    }
}