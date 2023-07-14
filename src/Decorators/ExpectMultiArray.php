<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ExpectMultiArray implements DecoratorInterface
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