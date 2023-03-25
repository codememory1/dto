<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Callback implements ConstraintInterface
{
    public function __construct(
        public readonly string $methodName
    ) {
    }

    public function getHandler(): string
    {
        return CallbackHandler::class;
    }
}