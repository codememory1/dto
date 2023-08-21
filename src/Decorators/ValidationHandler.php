<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;

final class ValidationHandler implements DecoratorHandlerInterface
{
    /**
     * @param Validation $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $context->getDataTransferObject()->addPropertyConstraints(
            $context->getDataTransferObject(),
            $context->getProperty()->getName(),
            $decorator->assert
        );
    }
}