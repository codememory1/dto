<?php

namespace Codememory\Dto\Collectors;

use Codememory\Dto\Exceptions\DecoratorHandlerNotRegisteredException;
use Codememory\Dto\Interfaces\CollectorInterface;
use Codememory\Dto\Interfaces\DecoratorHandlerRegistrarInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;

class BaseCollector implements CollectorInterface
{
    public function __construct(
        protected readonly DecoratorHandlerRegistrarInterface $decoratorHandlerRegistrar
    ) {
    }

    /**
     * @throws DecoratorHandlerNotRegisteredException
     */
    public function collect(ExecutionContextInterface $context, array $decorators): void
    {
        foreach ($decorators as $decorator) {
            $this->decoratorHandler($decorator, $context);

            if ($context->isSkippedThisProperty()) {
                break;
            }

            $this->nestedDecoratorsHandler($context);
        }
    }

    /**
     * @throws DecoratorHandlerNotRegisteredException
     */
    private function nestedDecoratorsHandler(ExecutionContextInterface $context): void
    {
        foreach ($context->getDecorators() as $decorator) {
            if ($decorator instanceof DecoratorInterface) {
                $this->decoratorHandler($decorator, $context);

                if ($context->isSkippedThisProperty()) {
                    break;
                }

                $this->nestedDecoratorsHandler($context);
            }
        }
    }

    /**
     * @throws DecoratorHandlerNotRegisteredException
     */
    private function decoratorHandler(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        if (!class_exists($decorator->getHandler())) {
            throw new DecoratorHandlerNotRegisteredException($decorator->getHandler());
        }

        $context->setDecorators([]);

        $this->decoratorHandlerRegistrar->getHandler($decorator->getHandler())->handle($decorator, $context);
    }
}