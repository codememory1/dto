<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ValueModifyingDecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE | Attribute::TARGET_PARAMETER)]
final class ExpectArray implements DecoratorInterface, ValueModifyingDecoratorInterface
{
    public function __construct(
        public readonly array $expectKeys
    ) {
    }

    public function getHandler(): string
    {
        return ExpectArrayHandler::class;
    }
}