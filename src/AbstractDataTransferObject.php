<?php

namespace Codememory\Dto;

use Codememory\Dto\Collection\DataTransferObjectPropertyConstraintsCollection;
use Codememory\Dto\Interfaces\CollectorInterface;
use Codememory\Dto\Interfaces\ConfigurationInterface;
use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Dto\Interfaces\ExecutionContextFactoryInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Dto\Validator\Constraints\Collection;
use Codememory\Reflection\ReflectorManager;
use Codememory\Reflection\Reflectors\ClassReflector;
use Codememory\Reflection\Reflectors\PropertyReflector;
use function is_array;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;

/**
 * @template Entity as mixed
 */
#[Collection('getListDataTransferObjectPropertyConstrainsCollection')]
abstract class AbstractDataTransferObject implements DataTransferObjectInterface
{
    protected array $_collectedDataForHarvestableObject = [];
    private ?object $harvestableObject = null;
    private ?ClassReflector $classReflector = null;
    private array $listDataTransferObjectPropertyConstrainsCollection = [];

    public function __construct(
        protected readonly CollectorInterface $_collector,
        protected readonly ConfigurationInterface $_configuration,
        protected readonly ExecutionContextFactoryInterface $_executionContextFactory,
        protected readonly ReflectorManager $_reflectorManager
    ) {
    }

    public function getCollector(): CollectorInterface
    {
        return $this->_collector;
    }

    public function getConfiguration(): ConfigurationInterface
    {
        return $this->_configuration;
    }

    public function getExecutionContextFactory(): ExecutionContextFactoryInterface
    {
        return $this->_executionContextFactory;
    }

    public function getReflectorManager(): ReflectorManager
    {
        return $this->_reflectorManager;
    }

    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public function getClassReflector(): ClassReflector
    {
        return $this->classReflector ?: $this->classReflector = $this->_reflectorManager->getReflector(static::class);
    }

    /**
     * @return null|Entity
     */
    public function getHarvestableObject(): ?object
    {
        return $this->harvestableObject;
    }

    public function setHarvestableObject(object $object): self
    {
        $this->harvestableObject = $object;

        return $this;
    }

    public function addDataTransferObjectPropertyConstraintsCollection(DataTransferObjectInterface $dataTransferObject, DataTransferObjectPropertyConstraintsCollection|array $dataTransferObjectPropertyConstraintsCollection): self
    {
        if (is_array($dataTransferObjectPropertyConstraintsCollection)) {
            foreach ($dataTransferObjectPropertyConstraintsCollection as $dataTransferObjectNamespace => $collection) {
                if ($collection instanceof DataTransferObjectPropertyConstraintsCollection) {
                    $this->listDataTransferObjectPropertyConstrainsCollection[$dataTransferObjectNamespace] = $collection;
                }
            }
        } else {
            $this->listDataTransferObjectPropertyConstrainsCollection[$dataTransferObject::class] = $dataTransferObjectPropertyConstraintsCollection;
        }

        return $this;
    }

    public function getListDataTransferObjectPropertyConstrainsCollection(): array
    {
        return $this->listDataTransferObjectPropertyConstrainsCollection;
    }

    public function getDataTransferObjectPropertyConstrainsCollection(string $dataTransferObjectNamespace): ?DataTransferObjectPropertyConstraintsCollection
    {
        return $this->listDataTransferObjectPropertyConstrainsCollection[$dataTransferObjectNamespace] ?? null;
    }

    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public function collect(array $data): self
    {
        $properties = $this->getConfiguration()->getDataTransferObjectPropertyProvider()->getProperties($this->getClassReflector());

        foreach ($properties as $property) {
            if ($property instanceof PropertyReflector) {
                $context = $this->_executionContextFactory->createExecutionContext($this, $property, $data);

                $this->propertyHandler($context);

                if ($context->isSkippedThisProperty()) {
                    break;
                }
            }
        }

        return $this;
    }

    public function recollectHarvestableObject(object $newObject): DataTransferObjectInterface
    {
        $this->setHarvestableObject($newObject);

        foreach ($this->_collectedDataForHarvestableObject as $setterName => $value) {
            $this->getHarvestableObject()->{$setterName}($value);
        }

        return $this;
    }

    private function propertyHandler(ExecutionContextInterface $context): void
    {
        $this->getCollector()->collect($context);

        $this->propertyHandlerForDataTransferObject($context);
        $this->propertyHandlerForHarvestableObject($context);
    }

    private function propertyHandlerForDataTransferObject(ExecutionContextInterface $context): void
    {
        $context->getProperty()->setValue($this, $context->getDataTransferObjectValue());
    }

    private function propertyHandlerForHarvestableObject(ExecutionContextInterface $context): void
    {
        if (null !== $this->getHarvestableObject() && !$context->isIgnoredSetterCallForHarvestableObject()) {
            $setterName = $context->getNameSetterMethodForHarvestableObject();
            $value = $context->getValueForHarvestableObject();

            $this->getHarvestableObject()->{$setterName}($value);

            $this->_collectedDataForHarvestableObject[$setterName] = $value;
        }
    }
}