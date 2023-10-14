<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\ReflectorManager;
use Codememory\Reflection\Reflectors\ClassReflector;
use Symfony\Component\Validator\Constraint;

interface DataTransferObjectInterface
{
    public function getCollector(): CollectorInterface;

    public function getConfigurationFactory(): ConfigurationFactoryInterface;

    public function getConfiguration(): ConfigurationInterface;

    public function getExecutionContextFactory(): ExecutionContextFactoryInterface;

    public function getDecoratorHandlerRegistrar(): DecoratorHandlerRegistrarInterface;

    public function getReflectorManager(): ReflectorManager;

    public function getClassReflector(): ClassReflector;

    public function getHarvestableObject(): ?object;

    public function setHarvestableObject(object $object): self;

    /**
     * @return array<string, array<int, Constraint>>
     */
    public function getPropertyConstraints(): array;

    public function mergePropertyConstraints(self $dataTransferObject): self;

    /**
     * @param array<int, Constraint> $constraints
     */
    public function addPropertyConstraints(self $dataTransferObject, string $propertyName, array $constraints): self;

    public function collect(array $data): self;

    public function recollectHarvestableObject(object $newObject): self;
}