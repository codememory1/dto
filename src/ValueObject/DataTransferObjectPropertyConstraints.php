<?php

namespace Codememory\Dto\ValueObject;

use Symfony\Component\Validator\Constraint;

final class DataTransferObjectPropertyConstraints
{
    /**
     * @param array<int, Constraint> $constraints
     */
    public function __construct(
        private readonly string $propertyName,
        private readonly array $constraints
    ) {
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }
}