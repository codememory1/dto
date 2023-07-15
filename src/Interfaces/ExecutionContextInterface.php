<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\Reflectors\PropertyReflector;

interface ExecutionContextInterface
{
    /**
     * Returns the current data transfer object that contains the property being processed.
     */
    public function getDataTransferObject(): DataTransferObjectInterface;

    /**
     * Returns the currently processed property.
     */
    public function getProperty(): PropertyReflector;

    /**
     * Returns the input data that will be used to collect the dto and the object.
     */
    public function getData(): array;

    /**
     * Returns a value from data (which was passed during data transfer build).
     */
    public function getDataValue(): mixed;

    public function setDataValue(mixed $value): self;

    /**
     * Returns the value that was set to the data transfer property.
     */
    public function getDataTransferObjectValue(): mixed;

    public function setDataTransferObjectValue(mixed $value): self;

    /**
     * Returns the value that was set to the object being collected.
     */
    public function getValueForHarvestableObject(): mixed;

    public function setValueForHarvestableObject(mixed $value): self;

    /**
     * Returns a key that can be used to get a value from data.
     */
    public function getDataKey(): string;

    public function setDataKey(string $key): self;

    /**
     * Returns the name of the setter method for the object being collected.
     */
    public function getNameSetterMethodForHarvestableObject(): mixed;

    public function setNameSetterMethodForHarvestableObject(string $name): self;

    /**
     * Whether the setter method call on the harvestable object is ignored.
     */
    public function isIgnoredSetterCallForHarvestableObject(): bool;

    public function setIgnoredSetterCallForHarvestableObject(bool $ignore): self;

    /**
     * Whether to skip processing the current property.
     */
    public function isSkippedThisProperty(): bool;

    public function setSkipThisProperty(bool $skip): self;
}