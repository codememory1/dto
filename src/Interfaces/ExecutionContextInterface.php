<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\Reflectors\PropertyReflector;

interface ExecutionContextInterface
{
    public function getManager(): DataTransferObjectManagerInterface;

    public function getDataTransferObject(): DataTransferObjectInterface;

    public function getProperty(): PropertyReflector;

    public function getInputData(): array;

    public function getValue(): mixed;

    public function setValue(mixed $value): self;

    public function isSkippedThisProperty(): bool;

    public function setSkipThisProperty(bool $skip): self;

    /**
     * @return array<int, DecoratorInterface>
     */
    public function getDecorators(): array;

    /**
     * @param array<int, DecoratorInterface> $decorators
     */
    public function setDecorators(array $decorators): self;

    public function addDecorator(DecoratorInterface $decorator): self;
}