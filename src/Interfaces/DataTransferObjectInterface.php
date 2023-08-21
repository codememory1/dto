<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Dto\Collection\DataTransferObjectPropertyConstraintsCollection;
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
     * @param array<int, DataTransferObjectPropertyConstraintsCollection>|DataTransferObjectPropertyConstraintsCollection $dataTransferObjectPropertyConstraintsCollection
     */
    public function addDataTransferObjectPropertyConstraintsCollection(self $dataTransferObject, DataTransferObjectPropertyConstraintsCollection|array $dataTransferObjectPropertyConstraintsCollection): self;

    /**
     * @return array<string, DataTransferObjectPropertyConstraintsCollection>
     */
    public function getListDataTransferObjectPropertyConstrainsCollection(): array;

    public function getDataTransferObjectPropertyConstrainsCollection(self $dataTransferObject): ?DataTransferObjectPropertyConstraintsCollection;

    /**
     * @param array<int, Constraint>  $constraints
     */
    public function addPropertyConstraints(DataTransferObjectInterface $dataTransferObject, string $propertyName, array $constraints): self;

    public function collect(array $data): self;

    public function recollectHarvestableObject(object $newObject): self;
}