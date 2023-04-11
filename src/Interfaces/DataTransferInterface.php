<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Dto\DataTransferCollection;
use Codememory\Dto\Registers\ConstraintHandlerRegister;
use Codememory\Reflection\ReflectorManager;
use Codememory\Reflection\Reflectors\ClassReflector;

interface DataTransferInterface
{
    public function getReflectorManager(): ReflectorManager;

    public function getReflector(): ClassReflector;

    public function getConstraintHandlerRegister(): ConstraintHandlerRegister;

    public function setObject(object $object): self;

    public function getObject(): ?object;

    /**
     * @param array<int, DataTransferCollection>|DataTransferCollection $dataTransferCollection
     */
    public function addDataTransferCollection(string $key, DataTransferCollection|array $dataTransferCollection): self;

    public function getListDataTransferCollection(): array;

    public function collect(array $data): self;
}