<?php

namespace Codememory\Dto;

use Codememory\Dto\Interfaces\CollectorInterface;
use Codememory\Dto\Interfaces\DataTransferInterface;
use Codememory\Dto\Registers\ConstraintHandlerRegister;
use Codememory\Dto\Validator\Constraints\Collection;
use Codememory\Reflection\ReflectorManager;
use Codememory\Reflection\Reflectors\ClassReflector;
use ReflectionProperty;
use function is_array;
use LogicException;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;

/**
 * @template Entity as mixed
 */
#[Collection('getListDataTransferCollection')]
class DataTransfer implements DataTransferInterface
{
    protected readonly ClassReflector $reflector;
    protected array $listDataTransferCollection = [];
    protected ?object $object = null;

    /**
     * @var array<string, mixed>
     */
    protected array $collectedObjectData = [];

    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected readonly CollectorInterface $collector,
        protected readonly ReflectorManager $reflectorManager,
        protected readonly ConstraintHandlerRegister $constraintHandlerRegister
    ) {
        $this->reflector = $reflectorManager->getReflector(static::class);
        $this->listDataTransferCollection[static::class] = new DataTransferCollection($this, []);
    }

    public function getReflectorManager(): ReflectorManager
    {
        return $this->reflectorManager;
    }

    public function getReflector(): ClassReflector
    {
        return $this->reflector;
    }

    public function getConstraintHandlerRegister(): ConstraintHandlerRegister
    {
        return $this->constraintHandlerRegister;
    }

    public function setObject(object $object): DataTransferInterface
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return null|Entity
     */
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
        $properties = $this->reflector->getPropertiesIncludingParent([DataTransfer::class], ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $dataTransferControl = new DataTransferControl($this, $property, $data);

            $this->collector->collect($dataTransferControl);

            if ($dataTransferControl->isSkipProperty()) {
                continue;
            }

            $this->dataTransferCollector($dataTransferControl);
            $this->objectCollector($dataTransferControl);
        }

        return $this;
    }

    public function recollectObject(object $newObject): DataTransferInterface
    {
        $this->setObject($newObject);

        foreach ($this->collectedObjectData as $setterName => $value) {
            $this->object->{$setterName}($value);
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
                $setterName = $dataTransferControl->getSetterMethodNameToObject();
                $value = $dataTransferControl->getObjectValue();

                $this->object->{$setterName}($value);

                $this->collectedObjectData[$setterName] = $value;
            }
        }
    }
}