<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ToEntityConstraint implements ConstraintInterface
{
    public function __construct(
        public readonly ?string $entity = null,
        public readonly ?string $byKey = null,
        public readonly ?string $whereCallback = null,         /* Method name of DataTransfer */
        public readonly ?string $entityNotFoundCallback = null /* Method name of DataTransfer */
    ) {
    }

    public function getHandler(): string
    {
        return ToEntityConstraintHandler::class;
    }
}