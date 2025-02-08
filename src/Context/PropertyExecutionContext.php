<?php

namespace Codememory\Dto\Context;

use Codememory\Dto\Interfaces\ClassExecutionContextInterface;
use Codememory\Dto\Interfaces\NameConverterInterface;
use Codememory\Dto\Interfaces\PropertyExecutionContextInterface;
use Codememory\Dto\Interfaces\PropertyWrapperInterface;

class PropertyExecutionContext implements PropertyExecutionContextInterface
{
    private string $inputName;
    private mixed $propertyValue;

    public function __construct(
        private readonly ClassExecutionContextInterface $classExecutionContext,
        private readonly PropertyWrapperInterface $propertyWrapper,
        private readonly NameConverterInterface $nameConverter
    ) {
    }

    public function getClassExecutionContext(): ClassExecutionContextInterface
    {
        return $this->classExecutionContext;
    }

    public function getPropertyWrapper(): PropertyWrapperInterface
    {
        return $this->propertyWrapper;
    }

    public function getInputName(): string
    {
        if (isset($this->inputName)) {
            return $this->inputName;
        }

        return $this->nameConverter->propertyNameToDataKey($this->propertyWrapper->getName());
    }

    public function setInputName(string $inputName): static
    {
        $this->inputName = $inputName;

        return $this;
    }

    public function getInputValue(): mixed
    {
        return $this->classExecutionContext->getData()[$this->getInputName()];
    }

    public function getPropertyValue(): mixed
    {
        if (isset($this->propertyValue)) {
            return $this->propertyValue;
        }

        return $this->getInputValue();
    }

    public function setPropertyValue(mixed $value): static
    {
        $this->propertyValue = $value;

        return $this;
    }
}