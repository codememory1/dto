<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\NonValueModifyingDecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE | Attribute::TARGET_PARAMETER)]
final class DynamicValidation implements DecoratorInterface, NonValueModifyingDecoratorInterface
{
    public function __construct(
        public readonly string $callbackName
    ) {
    }

    public function getHandler(): string
    {
        return DynamicValidationHandler::class;
    }
}