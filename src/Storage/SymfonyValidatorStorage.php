<?php

namespace Codememory\Dto\Storage;

use Codememory\Dto\Interfaces\StorageInterface;
use Codememory\Reflection\Reflectors\PropertyReflector;

class SymfonyValidatorStorage implements StorageInterface
{
    private array $value = [];

    public function addConstraints(PropertyReflector $propertyReflector, string $dto, array $constraints): self
    {
        $key = $this->buildKey($propertyReflector, $dto);

        if (!array_key_exists($key, $this->value)) {
            $this->value[$key] = [];
        }

        $this->value[$key] = array_merge($this->value[$key], $constraints);

        return $this;
    }

    public function getValue(): array
    {
        return $this->value;
    }

    private function buildKey(PropertyReflector $propertyReflector, string $dto): string
    {
        return sprintf('%s@%s', $dto, $propertyReflector->getName());
    }
}