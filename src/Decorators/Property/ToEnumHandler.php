<?php

namespace Codememory\Dto\Decorators\Property;

use function call_user_func;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorHandlerInterface;
use Codememory\Dto\Interfaces\PropertyExecutionContextInterface;
use function constant;

class ToEnumHandler implements PropertyDecoratorHandlerInterface
{
    /**
     * @param ToEnum $decorator
     */
    public function process(DecoratorInterface $decorator, PropertyExecutionContextInterface $executionContext): void
    {
        $enumName = $decorator->enum ?? $executionContext->getPropertyWrapper()->getPropertyReflector()->getType()->getName();

        if (!$decorator->value) {
            $executionContext->setPropertyValue(constant("{$enumName}::{$executionContext->getPropertyValue()}"));
        } else {
            $executionContext->setPropertyValue(call_user_func([$enumName, 'from'], $executionContext->getPropertyValue()));
        }
    }
}