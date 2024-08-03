<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\Reflectors\ClassReflector;

interface DataTransferObjectInterface
{
    public function getClassReflector(): ClassReflector;

    public function createStorage(StorageInterface $storage): self;

    public function existStorage(string $className): bool;

    /**
     * @template T of object
     *
     * @psalm-param class-string<T> $className
     *
     * @psalm-return T|null
     */
    public function getStorage(string $className): ?object;

    public function getClassName(): string;
}