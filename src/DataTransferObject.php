<?php

namespace Codememory\Dto;

use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Dto\Interfaces\StorageInterface;
use Codememory\Reflection\Reflectors\ClassReflector;

class DataTransferObject implements DataTransferObjectInterface
{
    protected array $storages = [];
    protected ?string $className = null;

    public function __construct(
        protected readonly ClassReflector $classReflector
    ) {
    }

    public function getClassReflector(): ClassReflector
    {
        return $this->classReflector;
    }

    public function createStorage(StorageInterface $storage): DataTransferObjectInterface
    {
        $this->storages[$storage::class] = $storage;

        return $this;
    }

    public function existStorage(string $className): bool
    {
        return array_key_exists($className, $this->storages);
    }

    public function getStorage(string $className): ?StorageInterface
    {
        return $this->storages[$className] ?? null;
    }

    public function getClassName(): string
    {
        return $this->classReflector->getName();
    }
}