<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use function gettype;
use function is_array;

final class ExpectOneDimensionalArrayHandler implements DecoratorHandlerInterface
{
    /**
     * @param ExpectOneDimensionalArray $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $values = [];

        foreach ($context->getValue() as $value) {
            if (!is_array($value) && ([] === $decorator->types || in_array(gettype($value), $decorator->types, true))) {
                $values[] = $value;
            }
        }

        $context->setValue($values);
    }
}