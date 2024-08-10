<?php

namespace Codememory\Dto;

use Codememory\Dto\Interfaces\CollectorInterface;
use Codememory\Dto\Interfaces\DataTransferObjectFactoryInterface;
use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Dto\Interfaces\DataTransferObjectManagerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextFactoryInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Dto\Interfaces\NonValueModifyingDecoratorInterface;
use Codememory\Dto\Interfaces\ValueModifyingDecoratorInterface;
use Codememory\Reflection\ReflectorManager;
use Codememory\Reflection\Reflectors\AttributeReflector;
use Codememory\Reflection\Reflectors\ClassReflector;
use ReflectionProperty;

class DataTransferObjectManager implements DataTransferObjectManagerInterface
{
    /**
     * @var array<int, callable>
     */
    protected array $postNonValueModifyingDecoratorCallbacks = [];

    /**
     * @var array<int, callable>
     */
    protected array $postProcessingDecoratorsCallbacks = [];

    public function __construct(
        protected readonly ReflectorManager $reflectorManager,
        protected readonly DataTransferObjectFactoryInterface $dataTransferObjectFactory,
        protected readonly ExecutionContextFactoryInterface $executionContextFactory,
        protected readonly CollectorInterface $collector
    ) {
    }

    public function addPostNonValueModifyingDecoratorCallback(callable $callback): DataTransferObjectManagerInterface
    {
        $this->postNonValueModifyingDecoratorCallbacks[] = $callback;

        return $this;
    }

    public function addPostProcessingDecoratorsCallback(callable $callback): DataTransferObjectManagerInterface
    {
        $this->postProcessingDecoratorsCallbacks[] = $callback;

        return $this;
    }

    public function create(string $className, array $inputData): object
    {
        $classReflector = $this->reflectorManager->getReflector($className);
        $dataTransferObject = $this->dataTransferObjectFactory->create($classReflector);
        $classAttributes = $classReflector->getAttributes();
        $build = $this->nonValueModifyingDecoratorsHandle($classReflector, $dataTransferObject, $classAttributes, $inputData);

        $this->valueModifyingDecoratorsHandle($build, $classAttributes);
        $this->executePostProcessingDecoratorsCallbacks($dataTransferObject, $build, $inputData);

        return $this->instance($classReflector, $build);
    }

    /**
     * @param array<int, AttributeReflector> $classAttributes
     */
    private function nonValueModifyingDecoratorsHandle(ClassReflector $classReflector, DataTransferObjectInterface $dataTransferObject, array $classAttributes, array $inputData): array
    {
        $classNonValueModifyingDecorators = $this->getNonValueModifyingDecorators($classAttributes);
        $build = [];

        foreach ($classReflector->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $context = $this->executionContextFactory->createExecutionContext($this, $dataTransferObject, $property, $inputData);

            $this->collector->collect($context, [
                ...$classNonValueModifyingDecorators,
                ...$this->getNonValueModifyingDecorators($property->getAttributes())
            ]);

            $build[$property->getName()] = $context;
        }

        foreach ($this->postNonValueModifyingDecoratorCallbacks as $callback) {
            $callback($dataTransferObject, $build, $inputData);
        }

        return $build;
    }

    /**
     * @param array<string, ExecutionContextInterface> $build
     * @param array<int, AttributeReflector>           $classAttributes
     */
    private function valueModifyingDecoratorsHandle(array $build, array $classAttributes): void
    {
        $classValueModifyingDecorators = $this->getValueModifyingDecorators($classAttributes);

        /** @var ExecutionContextInterface $context */
        foreach ($build as $context) {
            $this->collector->collect($context, [
                ...$classValueModifyingDecorators,
                ...$this->getValueModifyingDecorators($context->getProperty()->getAttributes())
            ]);
        }
    }

    /**
     * @param array<string, ExecutionContextInterface> $build
     */
    private function executePostProcessingDecoratorsCallbacks(DataTransferObjectInterface $dataTransferObject, array $build, array $inputData): void
    {
        foreach ($this->postProcessingDecoratorsCallbacks as $callback) {
            $callback($dataTransferObject, $build, $inputData);
        }

        $this->postProcessingDecoratorsCallbacks = [];
    }

    /**
     * @param array<int, AttributeReflector> $attributes
     *
     * @return array<int, DecoratorInterface&NonValueModifyingDecoratorInterface>
     */
    private function getNonValueModifyingDecorators(array $attributes): array
    {
        return array_filter(
            array_map(static fn (AttributeReflector $attribute) => $attribute->getInstance(), $attributes),
            static fn (object $instance) => $instance instanceof DecoratorInterface && $instance instanceof NonValueModifyingDecoratorInterface
        );
    }

    /**
     * @param array<int, AttributeReflector> $attributes
     *
     * @return array<int, DecoratorInterface&NonValueModifyingDecoratorInterface>
     */
    private function getValueModifyingDecorators(array $attributes): array
    {
        return array_filter(
            array_map(static fn (AttributeReflector $attribute) => $attribute->getInstance(), $attributes),
            static fn (object $instance) => $instance instanceof DecoratorInterface && $instance instanceof ValueModifyingDecoratorInterface
        );
    }

    private function instance(ClassReflector $classReflector, array $build): object
    {
        return $classReflector->newInstance(...array_map(static fn (ExecutionContextInterface $context) => $context->getValue(), $build));
    }
}