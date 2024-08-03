<?php

namespace Codememory\Dto\Decorators;

use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ValueModifyingDecoratorInterface;
use LogicException;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE | Attribute::TARGET_PARAMETER)]
final class ExpectOneDimensionalArray implements DecoratorInterface, ValueModifyingDecoratorInterface
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