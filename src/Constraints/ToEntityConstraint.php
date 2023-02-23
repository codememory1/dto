<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ToEntityConstraint implements ConstraintInterface
{
    public function __construct(
        public readonly string $byKey,
        public readonly bool $isList = false,
        public readonly bool $uniqueInList = true,
        public readonly bool $checkNotFoundEntity = true,
        public readonly ?string $customHandlerNotFoundEntity = null, /* Method name of DataTransfer */
        public readonly ?string $itemValueConverter = null,          /* Method name of DataTransfer */
        public readonly ?string $entity = null
    ) {
    }

    public function getHandler(): string
    {
        return ToEntityConstraintHandler::class;
    }
}