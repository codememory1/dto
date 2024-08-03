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
        $dto = $context->getDataTransferObject();

        if (!method_exists($dto->getClassName(), $decorator->methodName)) {
            throw new MethodNotFoundException($dto->getClassName(), $decorator->methodName);
        }

        $dto->getClassName()::{$decorator->methodName}($context);
    }
}