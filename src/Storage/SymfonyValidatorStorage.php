<?php

namespace Codememory\Dto\Storage;

use Codememory\Dto\Interfaces\StorageInterface;
use Codememory\Reflection\Reflectors\PropertyReflector;

class SymfonyValidatorStorage implements StorageInterface
{
    private array $value = [];

    public function addConstraints(PropertyReflector $propertyReflector, array $constraints): self
    {
        if (!array_key_exists($propertyReflector->getName(), $this->value)) {
            $this->value[$propertyReflector->getName()] = [];
        }

        $this->value[$propertyReflector->getName()] = [...$this->value[$propertyReflector->getName()], ...$constraints];

        return $this;
    }

    public function getValue(): array
    {
        return $this->value;
    }
}