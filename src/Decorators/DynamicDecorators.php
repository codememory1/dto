<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\NonValueModifyingDecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class DynamicDecorators implements DecoratorInterface, NonValueModifyingDecoratorInterface
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