<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\Interfaces\ConstraintInterface;
use LogicException;

final class ExpectOneDimensionalArray implements ConstraintInterface
{
    public const TYPES = [
        'integer', 'string', 'float', 'double', 'boolean'
    ];

    public function __construct(
        public readonly array $types = []
    ) {
        foreach ($this->types as $type) {
            if (!in_array($type, self::TYPES)) {
                throw new LogicException("Undefined type $type");
            }
        }
    }

    public function getHandler(): string
    {
        return ExpectOneDimensionalArrayHandler::class;
    }
}