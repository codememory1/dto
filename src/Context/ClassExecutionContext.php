<?php

namespace Codememory\Dto\Context;

use Codememory\Dto\Interfaces\ClassExecutionContextInterface;
use Codememory\Dto\Interfaces\DataTransferObjectManagerInterface;
use Codememory\Dto\Interfaces\PropertyWrapperFactoryInterface;
use Codememory\Reflection\Reflectors\ClassReflector;
use Codememory\Reflection\Reflectors\PropertyReflector;

class ClassExecutionContext implements ClassExecutionContextInterface
{
    private array $propertyWrappers;
    private array $metadata = [];

    public function __construct(
        private readonly DataTransferObjectManagerInterface $manager,
        private readonly PropertyWrapperFactoryInterface $propertyWrapperFactory,
        private readonly ClassReflector $reflector,
        private array $data
    ) {
        $this->propertyWrappers = array_map(
            fn (PropertyReflector $propertyReflector) => $this->propertyWrapperFactory->create($propertyReflector),
            $this->reflector->getProperties()
        );
    }

    public function getManager(): DataTransferObjectManagerInterface
    {
        return $this->manager;
    }

    public function getReflector(): ClassReflector
    {
        return $this->reflector;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getPropertyWrappers(): array
    {
        return $this->propertyWrappers;
    }

    public function setPropertyWrappers(array $propertyWrappers): static
    {
        $this->propertyWrappers = $propertyWrappers;

        return $this;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): static
    {
        $this->metadata = $metadata;

        return $this;
    }
}