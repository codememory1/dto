<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Callback implements DecoratorInterface
{
    public function __construct(
        public readonly string $methodName
    ) {
    }

    public function getHandler(): string
    {
        return CallbackHandler::class;
    }
}