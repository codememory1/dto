<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Collectors\BaseCollector;
use Codememory\Dto\Interfaces\ConstraintInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class NestedDTO implements ConstraintInterface
{
    public function __construct(
        public readonly string $dataTransfer,
        public readonly ?string $object = null,
        public readonly string $collector = BaseCollector::class
    ) {
    }

    public function getHandler(): string
    {
        return NestedDTOHandler::class;
    }
}