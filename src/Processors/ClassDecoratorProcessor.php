<?php

namespace Codememory\Dto\Processors;

use Codememory\Dto\Events\AfterProcessedClassDecoratorsEvent;
use Codememory\Dto\Events\BeforeProcessedClassDecoratorsEvent;
use Codememory\Dto\Exceptions\DataTransferObjectException;
use Codememory\Dto\Exceptions\DecoratorHandlerNotRegisteredException;
use Codememory\Dto\Interfaces\ClassDecoratorHandlerInterface;
use Codememory\Dto\Interfaces\ClassDecoratorInterface;
use Codememory\Dto\Interfaces\ClassDecoratorProcessorInterface;
use Codememory\Dto\Interfaces\ClassDecoratorRegistrarInterface;
use Codememory\Dto\Interfaces\ClassExecutionContextInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

readonly class ClassDecoratorProcessor implements ClassDecoratorProcessorInterface
{
    public function __construct(
        private ClassDecoratorRegistrarInterface $classDecoratorRegistrar,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @throws DataTransferObjectException
     */
    public function processDecorators(ClassExecutionContextInterface $classExecutionContext, array $data): void
    {
        $this->eventDispatcher->dispatch(new BeforeProcessedClassDecoratorsEvent($classExecutionContext, $data));

        foreach ($classExecutionContext->getReflector()->getAttributes() as $attribute) {
            $decorator = $attribute->getInstance();

            if ($decorator instanceof ClassDecoratorInterface) {
                $this->getDecoratorHandler($classExecutionContext, $decorator)->process($decorator, $classExecutionContext);
            }
        }

        $this->eventDispatcher->dispatch(new AfterProcessedClassDecoratorsEvent($classExecutionContext, $data));
    }

    /**
     * @throws DataTransferObjectException
     */
    private function getDecoratorHandler(ClassExecutionContextInterface $classExecutionContext, ClassDecoratorInterface $decorator): ClassDecoratorHandlerInterface
    {
        if (!$this->classDecoratorRegistrar->existsHandler($decorator->getHandler())) {
            throw new DecoratorHandlerNotRegisteredException($classExecutionContext->getReflector()->getName(), $decorator::class, $decorator->getHandler());
        }

        return $this->classDecoratorRegistrar->getHandler($decorator->getHandler());
    }
}