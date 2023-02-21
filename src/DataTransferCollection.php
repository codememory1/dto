<?php

namespace Codememory\Dto;

use Codememory\Dto\Interfaces\DataTransferInterface;

final class DataTransferCollection
{
    public function __construct(
        private readonly DataTransferInterface $dataTransfer,
        private array $propertyValidation = []
    ) {
    }

    public function getDataTransfer(): DataTransferInterface
    {
        return $this->dataTransfer;
    }

    public function getPropertyValidation(): array
    {
        return $this->propertyValidation;
    }

    public function addPropertyValidation(string $propertyName, array|object $constraints): self
    {
        $this->propertyValidation[$propertyName] = $constraints;

        return $this;
    }
}