<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ValueModifyingDecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE | Attribute::TARGET_PARAMETER)]
final class ExpectMultiArray implements DecoratorInterface, ValueModifyingDecoratorInterface
{
    /**
     * @param array<int, string> $expectKeys
     */
    public function __construct(
        public readonly array $expectKeys,
        public readonly bool $itemKeyAsNumber = true
    ) {
    }

    public function getHandler(): string
    {
        return ExpectMultiArrayHandler::class;
    }
}