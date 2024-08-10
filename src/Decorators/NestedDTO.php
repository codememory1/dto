<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ValueModifyingDecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class NestedDTO implements DecoratorInterface, ValueModifyingDecoratorInterface
{
    public function __construct(
        public readonly ?string $dto = null,
        public readonly ?string $fromKey = null
    ) {
    }

    public function getHandler(): string
    {
        return NestedDTOHandler::class;
    }
}