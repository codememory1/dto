<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class PrefixSetterMethodForHarvestableObject implements DecoratorInterface
{
    public function __construct(
        public readonly string $prefix
    ) {
    }

    public function getHandler(): string
    {
        return PrefixSetterMethodForHarvestableObjectHandler::class;
    }
}