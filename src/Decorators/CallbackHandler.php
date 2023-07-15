<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Exceptions\MethodNotFoundException;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;

final class CallbackHandler implements DecoratorHandlerInterface
{
    /**
     * @param Callback $decorator
     *
     * @throws MethodNotFoundException
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        if (!method_exists($context->getDataTransferObject(), $decorator->methodName)) {
            throw new MethodNotFoundException($context->getDataTransferObject()::class, $decorator->methodName);
        }

        $context->getDataTransferObject()->{$decorator->methodName}($context);
    }
}