<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Exceptions\MethodNotFoundException;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;

final class DynamicDecoratorsHandler implements DecoratorHandlerInterface
{
    /**
     * @param DynamicDecorators $decorator
     *
     * @throws MethodNotFoundException
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $context->setDecorators([
            ...$decorator->decorators,
            ...$this->getDecoratorsFromMethod($decorator, $context)
        ]);
    }

    /**
     * @param DynamicDecorators $decorator
     *
     * @throws MethodNotFoundException
     *
     * @return array<int, DecoratorInterface>
     */
    private function getDecoratorsFromMethod(DecoratorInterface $decorator, ExecutionContextInterface $context): array
    {
        $className = $context->getDataTransferObject()->getClassName();

        if (null !== $decorator->methodName) {
            if (!method_exists($className, $decorator->methodName)) {
                throw new MethodNotFoundException($className, $decorator->methodName);
            }

            return $className::{$decorator->methodName}($className) ?: [];
        }

        return [];
    }
}