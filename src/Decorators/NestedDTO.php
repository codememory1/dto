<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class NestedDTO implements DecoratorInterface
{
    public function __construct(
        public readonly string $dto,
        public readonly ?string $object = null,
        public readonly ?string $thenCallback = null
    ) {
    }

    public function getHandler(): string
    {
        return NestedDTOHandler::class;
    }
}