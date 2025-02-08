<?php

namespace Codememory\Dto\Wrappers;

use Codememory\Dto\Interfaces\PropertyWrapperInterface;
use Codememory\Reflection\Reflectors\PropertyReflector;

class PropertyWrapper implements PropertyWrapperInterface
{
    private string $name;
    private array $attributes;

    public function __construct(
        private readonly PropertyReflector $propertyReflector
    ) {
        $this->name = $this->propertyReflector->getName();
        $this->attributes = $this->propertyReflector->getAttributes();
    }

    public function getPropertyReflector(): PropertyReflector
    {
        return $this->propertyReflector;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }
}