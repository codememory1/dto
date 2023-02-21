<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\Interfaces\ConstraintInterface;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class NestedDataTransferConstraint implements ConstraintInterface
{
    public function __construct(
        public readonly string $dataTransfer,
        public readonly ?string $object = null
    ) {
    }

    public function getHandler(): string
    {
        return NestedDataTransferConstraintHandler::class;
    }
}