<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Dto\Collection\DataTransferObjectPropertyConstraintsCollection;
use Codememory\Reflection\ReflectorManager;
use Codememory\Reflection\Reflectors\ClassReflector;

interface DataTransferObjectInterface
{
    public function getCollector(): CollectorInterface;

    public function getConfiguration(): ConfigurationInterface;

    public function getExecutionContextFactory(): ExecutionContextFactoryInterface;

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

    public function getDataTransferObjectPropertyConstrainsCollection(string $dataTransferObjectNamespace): ?DataTransferObjectPropertyConstraintsCollection;

    public function collect(array $data): self;

    public function recollectHarvestableObject(object $newObject): self;
}