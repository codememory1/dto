<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\Interfaces\ConstraintInterface;

final class SetterMethod implements ConstraintInterface
{
    public function __construct(
        public readonly string $name
    ) {
    }

    public function getHandler(): string
    {
        return SetterMethodHandler::class;
    }
}