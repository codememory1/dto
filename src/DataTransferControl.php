<?php

namespace Codememory\Dto;

use Codememory\Dto\Interfaces\DataTransferInterface;
use ReflectionProperty;

final class DataTransferControl
{
    private mixed $dataTransferValue = null;
    private mixed $objectValue = null;
    private mixed $dataValue = null;
    private ?string $setterMethodNameToObject = null;
    private bool $ignoreSetterCall = false;
    private bool $skipProperty = false;
    private ?string $dataKey = null;

    public function __construct(
        public readonly DataTransferInterface $dataTransfer,
        public readonly ReflectionProperty $property,
        public readonly array $data
    ) {
    }

    public function getDataTransferValue(): mixed
    {
        return $this->dataTransferValue;
    }

    public function setDataTransferValue(mixed $value): self
    {
        $this->dataTransferValue = $value;

        return $this;
    }

    public function getObjectValue(): mixed
    {
        return $this->objectValue;
    }

    public function setObjectValue(mixed $value): self
    {
        $this->objectValue = $value;

        return $this;
    }

    public function getDataValue(): mixed
    {
        return $this->dataValue;
    }

    public function setDataValue(mixed $value): self
    {
        $this->dataValue = $value;

        return $this;
    }

    public function setValue(mixed $value): self
    {
        $this->setDataTransferValue($value);
        $this->setObjectValue($value);

        return $this;
    }

    public function getSetterMethodNameToObject(): ?string
    {
        return $this->setterMethodNameToObject;
    }

    public function setSetterMethodNameToObject(string $name): self
    {
        $this->setterMethodNameToObject = $name;

        return $this;
    }

    public function isIgnoreSetterCall(): bool
    {
        return $this->ignoreSetterCall;
    }

    public function setIsIgnoreSetterCall(bool $is): self
    {
        $this->ignoreSetterCall = $is;

        return $this;
    }

    public function isSkipProperty(): bool
    {
        return $this->skipProperty;
    }

    public function setIsSkipProperty(bool $is): self
    {
        $this->skipProperty = $is;

        return $this;
    }

    public function getDataKey(): ?string
    {
        return $this->dataKey;
    }

    public function setDataKey(string $key): self
    {
        $this->dataKey = $key;

        return $this;
    }
}