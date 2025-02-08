<?php

namespace Codememory\Dto\Processors;

use Codememory\Dto\Events\AfterAllProcessedTypeDecoratorsEvent;
use Codememory\Dto\Events\AfterProcessedTypeDecoratorsEvent;
use Codememory\Dto\Events\BeforeAllProcessedTypeDecoratorsEvent;
use Codememory\Dto\Events\BeforeProcessedTypeDecoratorsEvent;
use Codememory\Dto\Exceptions\DataTransferObjectException;
use Codememory\Dto\Exceptions\DecoratorHandlerNotRegisteredException;
use Codememory\Dto\Interfaces\ClassExecutionContextInterface;
use Codememory\Dto\Interfaces\DecoratorTypeRegistrarInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorHandlerInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorProcessorInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorRegistrarInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

readonly class PropertyDecoratorProcessor implements PropertyDecoratorProcessorInterface
{
    public function __construct(
        private DecoratorTypeRegistrarInterface $decoratorTypeRegistrar,
        private PropertyDecoratorRegistrarInterface $propertyDecoratorRegistrar,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @throws DataTransferObjectException
     */
    public function processDecorators(array $groupedProperties, ClassExecutionContextInterface $classExecutionContext, array $data): array
    {
        $propertyExecutionContexts = [];

        $this->eventDispatcher->dispatch(new BeforeAllProcessedTypeDecoratorsEvent($classExecutionContext, $data));

        foreach ($this->decoratorTypeRegistrar->getAllTypes() as $type) {
            $propertyMetadata = $groupedProperties[$type] ?? [];

            if (count($propertyMetadata) > 0) {
                $this->eventDispatcher->dispatch(new BeforeProcessedTypeDecoratorsEvent($classExecutionContext, $type, $data));
            }

            foreach ($propertyMetadata as $metadata) {
                foreach ($metadata->getAttributeInstances() as $decorator) {
                    $this
                        ->getDecoratorHandler($classExecutionContext, $decorator)
                        ->process($decorator, $metadata->getPropertyExecutionContext());
                }

                $propertyExecutionContexts[] = $metadata->getPropertyExecutionContext();
            }

            if (count($propertyMetadata) > 0) {
                $this->eventDispatcher->dispatch(new AfterProcessedTypeDecoratorsEvent($classExecutionContext, $type, $data));
            }
        }

        $this->eventDispatcher->dispatch(new AfterAllProcessedTypeDecoratorsEvent($propertyExecutionContexts, $classExecutionContext, $data));

        return $propertyExecutionContexts;
    }

    /**
     * @throws DataTransferObjectException
     */
    private function getDecoratorHandler(ClassExecutionContextInterface $classExecutionContext, PropertyDecoratorInterface $decorator): PropertyDecoratorHandlerInterface
    {
        if (!$this->propertyDecoratorRegistrar->existsHandler($decorator->getHandler())) {
            throw new DecoratorHandlerNotRegisteredException($classExecutionContext->getReflector()->getName(), $decorator::class, $decorator->getHandler());
        }

        return $this->propertyDecoratorRegistrar->getHandler($decorator->getHandler());
    }
}