<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use function constant;
use function defined;
use function is_string;

final class ToEnumHandler implements DecoratorHandlerInterface
{
    /**
     * @param ToEnum $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $enum = $context->getProperty()->getType()->getName();
        $value = $context->getValue();

        if (is_string($value) || is_numeric($value) && (class_exists($enum))) {
            $newValue = null;

            if (!$decorator->byValue) {
                $casePath = "{$enum}::{$value}";

                if (defined($casePath)) {
                    $newValue = constant($casePath);
                }
            } else {
                $newValue = $enum::tryFrom($value);
            }

            $context->setValue($newValue);
        }
    }
}