<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
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