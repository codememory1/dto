<?php

namespace Codememory\Dto\Context;

use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Dto\Interfaces\DataTransferObjectManagerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Reflection\Reflectors\PropertyReflector;

final class ExecutionContext implements ExecutionContextInterface
{
    private mixed $value = null;
    private bool $skippedThisProperty = false;
    private array $decorators = [];

    public function __construct(
        private readonly DataTransferObjectManagerInterface $manager,
        private readonly DataTransferObjectInterface $dataTransferObject,
        private readonly PropertyReflector $property,
        private readonly array $inputData
    ) {
    }

    public function getManager(): DataTransferObjectManagerInterface
    {
        return $this->manager;
    }

    public function getDataTransferObject(): DataTransferObjectInterface
    {
        return $this->dataTransferObject;
    }

    public function getProperty(): PropertyReflector
    {
        return $this->property;
    }

    public function getInputData(): array
    {
        return $this->inputData;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): ExecutionContextInterface
    {
        $this->value = $value;

        return $this;
    }

    public function isSkippedThisProperty(): bool
    {
        return $this->skippedThisProperty;
    }

    public function setSkipThisProperty(bool $skip): ExecutionContextInterface
    {
        $this->skippedThisProperty = $skip;

        return $this;
    }

    public function getDecorators(): array
    {
        return $this->decorators;
    }

    public function setDecorators(array $decorators): ExecutionContextInterface
    {
        $this->decorators = $decorators;

        return $this;
    }

    public function addDecorator(DecoratorInterface $decorator): ExecutionContextInterface
    {
        $this->decorators[] = $decorator;

        return $this;
    }
}