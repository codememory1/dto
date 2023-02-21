<?php

namespace Codememory\Dto\Adapter;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

final class ReflectionAdapter
{
    private readonly ReflectionClass $reflection;

    /**
     * @throws ReflectionException
     */
    public function __construct(
        private readonly string $namespace
    ) {
        $this->reflection = new ReflectionClass($this->namespace);
    }

    public function getClassAttributes(): array
    {
        return $this->reflection->getAttributes();
    }

    /**
     * @return array<int, ReflectionProperty>
     */
    public function getProperties(): array
    {
        return $this->reflection->getProperties(ReflectionProperty::IS_PUBLIC);
    }
}