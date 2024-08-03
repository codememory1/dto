<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use function constant;
use function defined;

final class ToEnumListHandler implements DecoratorHandlerInterface
{
    /**
     * @param ToEnumList $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $values = [];
        $dataValues = $decorator->unique ? array_unique($context->getValue()) : $context->getValue();

        foreach ($dataValues as $value) {
            $enumCasePath = "{$decorator->enum}::{$value}";

            if (defined($enumCasePath)) {
                $values[] = $decorator->byValue ? $decorator->enum::tryFrom($value) : constant($enumCasePath);
            }
        }

        $context->setValue($values);
    }
}