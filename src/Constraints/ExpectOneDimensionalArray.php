<?php

namespace Codememory\Dto\Constraints;

use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;
use LogicException;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ExpectOneDimensionalArray implements ConstraintInterface
{
    public const TYPES = [
        'integer', 'string', 'float', 'double', 'boolean'
    ];

    public function __construct(
        public readonly array $types = []
    ) {
        foreach ($this->types as $type) {
            if (!in_array($type, self::TYPES, true)) {
                throw new LogicException("Undefined type {$type}");
            }
        }
    }

    public function getHandler(): string
    {
        return ExpectOneDimensionalArrayHandler::class;
    }
}