<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ToEntityList implements ConstraintInterface
{
    public function __construct(
        public readonly ?string $entity = null,
        public readonly ?string $byKey = null,
        public readonly ?string $whereCallback = null,          /* Method name of DataTransfer */
        public readonly bool $unique = true,
        public readonly ?string $valueConverterCallback = null  /* Method name of DataTransfer */
    ) {
    }

    public function getHandler(): string
    {
        return ToEntityListHandler::class;
    }
}