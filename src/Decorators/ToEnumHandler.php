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
        $value = null;

        if (class_exists($enum)) {
            $dataValue = $context->getDataTransferObjectValue();

            if (is_string($dataValue) || is_numeric($dataValue)) {
                if (!$decorator->byValue) {
                    $casePath = "{$enum}::{$dataValue}";

                    if (defined($casePath)) {
                        $value = constant($casePath);
                    }
                } else {
                    $value = $enum::tryFrom($dataValue);
                }
            }
        }

        $context->setDataTransferObjectValue($value);
    }
}