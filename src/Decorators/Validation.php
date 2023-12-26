<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Validation implements DecoratorInterface
{
    public function __construct(
        public readonly array $assert
    ) {
    }

    public function getHandler(): string
    {
        return ValidationHandler::class;
    }
}