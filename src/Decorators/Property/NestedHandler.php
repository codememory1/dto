<?php

namespace Codememory\Dto\Decorators\Property;

use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorHandlerInterface;
use Codememory\Dto\Interfaces\PropertyExecutionContextInterface;
use function is_array;

class NestedHandler implements PropertyDecoratorHandlerInterface
{
    /**
     * @param Nested $decorator
     */
    public function process(DecoratorInterface $decorator, PropertyExecutionContextInterface $executionContext): void
    {
        $className = $decorator->className ?? $executionContext->getPropertyWrapper()->getPropertyReflector()->getType()->getName();
        $data = $executionContext->getPropertyValue();

        if (is_array($data)) {
            if (null === $decorator->className) {
                $executionContext->setPropertyValue($this->hydrate($executionContext, $className, $data));
            } else {
                $objects = [];

                foreach ($data as $item) {
                    if (is_array($item)) {
                        $objects[] = $this->hydrate($executionContext, $className, $item);
                    }
                }

                $executionContext->setPropertyValue($objects);
            }
        }
    }

    private function hydrate(PropertyExecutionContextInterface $executionContext, string $className, array $data): object
    {
        return $executionContext->getClassExecutionContext()->getManager()->hydrate($className, $data);
    }
}