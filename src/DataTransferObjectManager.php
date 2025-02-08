<?php

namespace Codememory\Dto;

use Codememory\Dto\Interfaces\ClassDecoratorProcessorInterface;
use Codememory\Dto\Interfaces\ClassExecutionContextFactoryInterface;
use Codememory\Dto\Interfaces\DataTransferObjectManagerInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorProcessorInterface;
use Codememory\Dto\Interfaces\PropertyGrouperInterface;
use Codememory\Reflection\ReflectorManager;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;

readonly class DataTransferObjectManager implements DataTransferObjectManagerInterface
{
    public function __construct(
        private ReflectorManager $reflectorManager,
        private PropertyGrouperInterface $propertyGrouper,
        private ClassDecoratorProcessorInterface $classDecoratorProcessor,
        private PropertyDecoratorProcessorInterface $propertyDecoratorProcessor,
        private ClassExecutionContextFactoryInterface $classExecutionContextFactory
    ) {
    }

    /**
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function hydrate(string $dataTransferObjectClassName, array $data): object
    {
        $reflector = $this->reflectorManager->getReflector($dataTransferObjectClassName);

        $classExecutionContext = $this->classExecutionContextFactory->create($this, $reflector, $data);

        $this->classDecoratorProcessor->processDecorators($classExecutionContext, $data);

        $propertyExecutionContexts = $this->propertyDecoratorProcessor->processDecorators(
            $this->propertyGrouper->groupProperties($classExecutionContext),
            $classExecutionContext,
            $data
        );

        $args = [];

        foreach ($propertyExecutionContexts as $propertyExecutionContext) {
            $args[$propertyExecutionContext->getPropertyWrapper()->getName()] = $propertyExecutionContext->getPropertyValue();
        }

        return $reflector->newInstance(...$args);
    }
}