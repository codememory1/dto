<?php

namespace Codememory\Dto;

use Codememory\Dto\Collection\DataTransferObjectPropertyConstraintsCollection;
use Codememory\Dto\Interfaces\CollectorInterface;
use Codememory\Dto\Interfaces\ConfigurationFactoryInterface;
use Codememory\Dto\Interfaces\ConfigurationInterface;
use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Dto\Interfaces\DecoratorHandlerRegistrarInterface;
use Codememory\Dto\Interfaces\ExecutionContextFactoryInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Dto\Validator\Constraints\Collection;
use Codememory\Dto\ValueObject\DataTransferObjectPropertyConstraints;
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
    protected ConfigurationInterface $_configuration;
    protected array $_collectedDataForHarvestableObject = [];
    protected ?object $_harvestableObject = null;
    protected ClassReflector $_classReflector;
    protected array $_listDataTransferObjectPropertyConstrainsCollection = [];

    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected readonly CollectorInterface $_collector,
        protected readonly ConfigurationFactoryInterface $_configurationFactory,
        protected readonly ExecutionContextFactoryInterface $_executionContextFactory,
        protected readonly DecoratorHandlerRegistrarInterface $_decoratorHandlerRegistrar,
        protected readonly ReflectorManager $_reflectorManager
    ) {
        $this->_configuration = $this->_configurationFactory->createConfiguration();
        $this->_classReflector = $this->_reflectorManager->getReflector(static::class);
    }

    public function getCollector(): CollectorInterface
    {
        return $this->_collector;
    }

    public function getConfigurationFactory(): ConfigurationFactoryInterface
    {
        return $this->_configurationFactory;
    }

    public function getConfiguration(): ConfigurationInterface
    {
        return $this->_configuration;
    }

    public function getExecutionContextFactory(): ExecutionContextFactoryInterface
    {
        return $this->_executionContextFactory;
    }

    public function getDecoratorHandlerRegistrar(): DecoratorHandlerRegistrarInterface
    {
        return $this->_decoratorHandlerRegistrar;
    }

    public function getReflectorManager(): ReflectorManager
    {
        return $this->_reflectorManager;
    }

    public function getClassReflector(): ClassReflector
    {
        return $this->_classReflector;
    }

    /**
     * @return null|Entity
     */
    public function getHarvestableObject(): ?object
    {
        return $this->_harvestableObject;
    }

    public function setHarvestableObject(object $object): self
    {
        $this->_harvestableObject = $object;

        return $this;
    }

    public function addDataTransferObjectPropertyConstraintsCollection(DataTransferObjectInterface $dataTransferObject, DataTransferObjectPropertyConstraintsCollection|array $dataTransferObjectPropertyConstraintsCollection): self
    {
        if (is_array($dataTransferObjectPropertyConstraintsCollection)) {
            foreach ($dataTransferObjectPropertyConstraintsCollection as $dataTransferObjectNamespace => $collection) {
                if ($collection instanceof DataTransferObjectPropertyConstraintsCollection) {
                    $this->_listDataTransferObjectPropertyConstrainsCollection[$dataTransferObjectNamespace] = $collection;
                }
            }
        } else {
            $this->_listDataTransferObjectPropertyConstrainsCollection[$dataTransferObject::class] = $dataTransferObjectPropertyConstraintsCollection;
        }

        return $this;
    }

    public function getListDataTransferObjectPropertyConstrainsCollection(): array
    {
        return $this->_listDataTransferObjectPropertyConstrainsCollection;
    }

    public function getDataTransferObjectPropertyConstrainsCollection(DataTransferObjectInterface $dataTransferObject): ?DataTransferObjectPropertyConstraintsCollection
    {
        return $this->listDataTransferObjectPropertyConstrainsCollection[$dataTransferObject::class] ?? null;
    }

    public function addPropertyConstraints(DataTransferObjectInterface $dataTransferObject, string $propertyName, array $constraints): self
    {
        $collection = $this->getDataTransferObjectPropertyConstrainsCollection($dataTransferObject);

        if (null !== $collection) {
            $collection->addDataTransferObjectPropertyConstraints($propertyName, $constraints);
        } else {
            $collection = new DataTransferObjectPropertyConstraintsCollection($dataTransferObject, [
                new DataTransferObjectPropertyConstraints($propertyName, $constraints)
            ]);

            $this->addDataTransferObjectPropertyConstraintsCollection($dataTransferObject, $collection);
        }

        return $this;
    }

    public function collect(array $data): self
    {
        $properties = $this->getConfiguration()->getDataTransferObjectPropertyProvider()->getProperties($this->getClassReflector());

        foreach ($properties as $property) {
            if ($property instanceof PropertyReflector) {
                $context = $this->_executionContextFactory->createExecutionContext($this, $property, $data);

                $this->propertyHandler($context);
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