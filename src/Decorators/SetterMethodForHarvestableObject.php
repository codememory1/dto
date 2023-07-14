<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class SetterMethodForHarvestableObject implements DecoratorInterface
{
    public function __construct(
        public readonly string $name
    ) {
    }

    public function getHandler(): string
    {
        return SetterMethodForHarvestableObjectHandler::class;
    }
}