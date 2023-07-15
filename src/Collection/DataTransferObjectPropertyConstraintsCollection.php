<?php

namespace Codememory\Dto\Collection;

use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Dto\ValueObject\DataTransferObjectPropertyConstraints;
use Symfony\Component\Validator\Constraint;

final class DataTransferObjectPropertyConstraintsCollection
{
    /**
     * @param array<int, DataTransferObjectPropertyConstraints> $listDataTransferObjectPropertyConstraints
     */
    public function __construct(
        private readonly DataTransferObjectInterface $dataTransferObject,
        private array $listDataTransferObjectPropertyConstraints
    ) {
    }

    public function getDataTransfer(): DataTransferObjectInterface
    {
        return $this->dataTransferObject;
    }

    public function getListDataTransferObjectPropertyConstraints(): array
    {
        return $this->listDataTransferObjectPropertyConstraints;
    }

    /**
     * @param array<int, Constraint> $constrains
     */
    public function addDataTransferObjectPropertyConstraints(string $propertyName, array $constrains): self
    {
        $this->listDataTransferObjectPropertyConstraints[] = new DataTransferObjectPropertyConstraints($propertyName, $constrains);

        return $this;
    }
}