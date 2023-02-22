<?php

namespace Codememory\Dto;

use Codememory\Dto\Adapter\ReflectionAdapter;
use Codememory\Dto\Interfaces\CollectorInterface;
use Codememory\Dto\Interfaces\DataTransferInterface;
use Codememory\Dto\Validator\Constraints\Collection;
use function is_array;
use LogicException;

#[Collection('getListDataTransferCollection')]
class DataTransfer implements DataTransferInterface
{
    protected readonly ReflectionAdapter $reflectionAdapter;
    protected array $listDataTransferCollection = [];
    protected ?object $object = null;

    public function __construct(
        protected readonly CollectorInterface $collector
    ) {
        $this->reflectionAdapter = new ReflectionAdapter(static::class);
        $this->listDataTransferCollection[static::class] = new DataTransferCollection($this, []);
    }

    public function getReflectionAdapter(): ReflectionAdapter
    {
        return $this->reflectionAdapter;
    }

    public function setObject(object $object): DataTransferInterface
    {
        $this->object = $object;

        return $this;
    }

    public function getObject(): ?object
    {
        return $this->object;
    }

    public function addDataTransferCollection(string $key, DataTransferCollection|array $dataTransferCollection): self
    {
        if (is_array($dataTransferCollection)) {
            foreach ($dataTransferCollection as $key => $collection) {
                if (!$collection instanceof DataTransferCollection) {
                    throw new LogicException(sprintf('One of the array elements is not %s', DataTransferCollection::class));
                }

                $this->listDataTransferCollection[$key] = $collection;
            }
        } else {
            $this->listDataTransferCollection[$key] = $dataTransferCollection;
        }

        return $this;
    }

    public function getListDataTransferCollection(): array
    {
        return $this->listDataTransferCollection;
    }

    public function collect(array $data): DataTransferInterface
    {
        foreach ($this->reflectionAdapter->getProperties() as $property) {
            $dataTransferControl = new DataTransferControl($this, $property, $data);

            $this->collector->collect($dataTransferControl);

            if ($dataTransferControl->isSkipProperty()) {
                break;
            }

            $this->dataTransferCollector($dataTransferControl);
            $this->objectCollector($dataTransferControl);
        }

        return $this;
    }

    private function dataTransferCollector(DataTransferControl $dataTransferControl): void
    {
        $dataTransferControl->property->setValue($this, $dataTransferControl->getDataTransferValue());
    }

    private function objectCollector(DataTransferControl $dataTransferControl): void
    {
        if (null !== $this->object) {
            if (!$dataTransferControl->isIgnoreSetterCall()) {
                $this->object->{$dataTransferControl->getSetterMethodNameToObject()}($dataTransferControl->getObjectValue());
            }
        }
    }
}