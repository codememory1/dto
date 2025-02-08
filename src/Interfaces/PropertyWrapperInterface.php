<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\Reflectors\PropertyReflector;

interface PropertyWrapperInterface
{
    public function getPropertyReflector(): PropertyReflector;

    public function getName(): string;

    public function setName(string $name): static;

    /**
     * @return array<int, object>
     */
    public function getAttributes(): array;

    /**
     * @param array<int, object> $attributes
     */
    public function setAttributes(array $attributes): static;
}