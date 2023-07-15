<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;

final class SetterMethodForHarvestableObjectHandler implements DecoratorHandlerInterface
{
    /**
     * @param SetterMethodForHarvestableObject $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $context->setNameSetterMethodForHarvestableObject($decorator->name);
    }
}