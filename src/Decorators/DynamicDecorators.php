<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class DynamicDecorators implements DecoratorInterface
{
    public function __construct(
        public readonly array $decorators = [],
        public readonly ?string $methodName = null
    ) {
    }

    public function getHandler(): string
    {
        return DynamicDecoratorsHandler::class;
    }
}