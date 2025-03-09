<?php

namespace Codememory\Dto\Decorators\Property;

use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorHandlerInterface;
use Codememory\Dto\Interfaces\PropertyExecutionContextInterface;

class DynamicNestedHandler implements PropertyDecoratorHandlerInterface
{
    /**
     * @param DynamicNested $decorator
     */
    public function process(DecoratorInterface $decorator, PropertyExecutionContextInterface $executionContext): void
    {
        $data = $executionContext->getPropertyValue();

        if (is_array($data)) {
            $executionContext->setPropertyValue($this->hydrate($executionContext, $this->getDtoClassName($decorator, $executionContext), $data));
        }
    }

    private function getClassName(DynamicNested $decorator, PropertyExecutionContextInterface $executionContext): string
    {
        if (is_array($decorator->callback)) {
            return $decorator->callback[0];
        }

        return $executionContext->getClassExecutionContext()->getReflector()->getName();
    }

    private function getMethodName(DynamicNested $decorator): string
    {
        if (is_array($decorator->callback)) {
            return $decorator->callback[0];
        }

        return $decorator->callback;
    }

    private function getDtoClassName(DynamicNested $decorator, PropertyExecutionContextInterface $executionContext): string
    {
        return $this->getClassName($decorator, $executionContext)::{$this->getMethodName($decorator)}(
            $executionContext->getClassExecutionContext()->getData()
        );
    }

    private function hydrate(PropertyExecutionContextInterface $executionContext, string $className, array $data): object
    {
        return $executionContext->getClassExecutionContext()->getManager()->hydrate($className, $data);
    }
}