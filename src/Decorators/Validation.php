<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\NonValueModifyingDecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE | Attribute::TARGET_PARAMETER)]
final class Validation implements DecoratorInterface, NonValueModifyingDecoratorInterface
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