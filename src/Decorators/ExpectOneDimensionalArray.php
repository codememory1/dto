<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;
use LogicException;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ExpectOneDimensionalArray implements DecoratorInterface
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