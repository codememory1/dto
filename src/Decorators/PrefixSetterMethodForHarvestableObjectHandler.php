<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use function Symfony\Component\String\u;

final class PrefixSetterMethodForHarvestableObjectHandler implements DecoratorHandlerInterface
{
    /**
     * @param PrefixSetterMethodForHarvestableObject $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $context->setNameSetterMethodForHarvestableObject(u("{$decorator->prefix}_{$context->getProperty()->getName()}")->camel());
    }
}