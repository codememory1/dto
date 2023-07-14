<?php

namespace Codememory\Dto\Context;

use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Reflection\Reflectors\PropertyReflector;

final class ExecutionContext implements ExecutionContextInterface
{
    private mixed $dataValue = null;
    private mixed $dataTransferValue = null;
    private mixed $valueForHarvestableObject = null;
    private string $dataKey;
    private mixed $nameSetterMethodForHarvestableObject = null;
    private bool $ignoredSetterCallForHarvestableObject = false;
    private bool $skippedThisProperty = false;

    public function __construct(
        private readonly DataTransferObjectInterface $dataTransferObject,
        private readonly PropertyReflector $property,
        private readonly array $data
    ) {
        $this->dataKey = $this->dataTransferObject->getConfiguration()->getDataKeyNamingStrategy()->convert($this->property->getName());
    }

    public function getDataTransferObject(): DataTransferObjectInterface
    {
        return $this->dataTransferObject;
    }

    public function getProperty(): PropertyReflector
    {
        return $this->property;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getDataValue(): mixed
    {
        return $this->dataValue;
    }

    public function setDataValue(mixed $value): ExecutionContextInterface
    {
        $this->dataValue = $value;

        return $this;
    }

    public function getDataTransferObjectValue(): mixed
    {
        return $this->dataTransferValue;
    }

    public function setDataTransferObjectValue(mixed $value): ExecutionContextInterface
    {
        $this->dataTransferValue = $value;

        return $this;
    }

    public function getValueForHarvestableObject(): mixed
    {
        return $this->valueForHarvestableObject;
    }

    public function setValueForHarvestableObject(mixed $value): ExecutionContextInterface
    {
        $this->valueForHarvestableObject = $value;

        return $this;
    }

    public function getDataKey(): string
    {
        return $this->dataKey;
    }

    public function setDataKey(string $key): ExecutionContextInterface
    {
        $this->dataKey = $key;

        return $this;
    }

    public function getNameSetterMethodForHarvestableObject(): mixed
    {
        return $this->nameSetterMethodForHarvestableObject;
    }

    public function setNameSetterMethodForHarvestableObject(string $name): ExecutionContextInterface
    {
        $this->nameSetterMethodForHarvestableObject = $name;

        return $this;
    }

    public function isIgnoredSetterCallForHarvestableObject(): bool
    {
        return $this->ignoredSetterCallForHarvestableObject;
    }

    public function setIgnoredSetterCallForHarvestableObject(bool $ignore): ExecutionContextInterface
    {
        $this->ignoredSetterCallForHarvestableObject = $ignore;

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
}