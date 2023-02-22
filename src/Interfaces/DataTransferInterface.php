<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Dto\Adapter\ReflectionAdapter;
use Codememory\Dto\DataTransferCollection;

interface DataTransferInterface
{
    public function getReflectionAdapter(): ReflectionAdapter;

    public function setObject(object $object): self;

    public function getObject(): ?object;

    /**
     * @param array<int, DataTransferCollection>|DataTransferCollection $dataTransferCollection
     */
    public function addDataTransferCollection(string $key, DataTransferCollection|array $dataTransferCollection): self;

    public function getListDataTransferCollection(): array;

    public function collect(array $data): self;
}