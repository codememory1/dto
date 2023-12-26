<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class ExpectArray implements DecoratorInterface
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