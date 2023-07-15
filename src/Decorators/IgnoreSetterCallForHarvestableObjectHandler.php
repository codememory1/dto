<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;

final class IgnoreSetterCallForHarvestableObjectHandler implements DecoratorHandlerInterface
{
    /**
     * @param IgnoreSetterCallForHarvestableObject $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $context->setIgnoredSetterCallForHarvestableObject(true);
    }
}