<?php

namespace Codememory\Dto\Interfaces;

use Psr\Cache\InvalidArgumentException;
use ReflectionException;

interface DataTransferObjectManagerInterface
{
    public function addPostNonValueModifyingDecoratorCallback(callable $callback): self;

    public function addPostProcessingDecoratorsCallback(callable $callback): self;

    /**
     * @template T of object
     *
     * @psalm-param class-string<T> $className
     *
     * @psalm-return T
     *
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public function create(string $className, array $inputData): object;
}