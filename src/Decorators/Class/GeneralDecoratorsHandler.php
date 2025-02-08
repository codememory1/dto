<?php

namespace Codememory\Dto\Decorators\Class;

use Codememory\Dto\Interfaces\ClassDecoratorHandlerInterface;
use Codememory\Dto\Interfaces\ClassExecutionContextInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;

class GeneralDecoratorsHandler implements ClassDecoratorHandlerInterface
{
    /**
     * @param GeneralDecorators $decorator
     */
    public function process(DecoratorInterface $decorator, ClassExecutionContextInterface $executionContext): void
    {
        foreach ($executionContext->getPropertyWrappers() as $propertyWrapper) {
            $propertyWrapper->setAttributes([
                ...$propertyWrapper->getAttributes(),
                ...$decorator->propertyDecorators
            ]);
        }
    }
}