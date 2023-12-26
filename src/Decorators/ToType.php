<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class ToType implements DecoratorInterface
{
    public function __construct(
        public readonly ?string $type = null,
        public readonly bool $onlyData = false
    ) {
    }

    public function getHandler(): string
    {
        return ToTypeHandler::class;
    }
}