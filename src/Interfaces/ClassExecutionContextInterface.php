<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\Reflectors\ClassReflector;

interface ClassExecutionContextInterface
{
    public function getManager(): DataTransferObjectManagerInterface;

    public function getReflector(): ClassReflector;

    public function getData(): array;

    public function setData(array $data): static;

    /**
     * @return array<int, PropertyWrapperInterface>
     */
    public function getPropertyWrappers(): array;

    /**
     * @param array<int, PropertyWrapperInterface> $propertyWrappers
     */
    public function setPropertyWrappers(array $propertyWrappers): static;

    public function getMetadata(): array;

    public function setMetadata(array $metadata): static;
}