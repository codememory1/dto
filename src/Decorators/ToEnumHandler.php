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

        $context->setDataTransferObjectValue(null);

        if (class_exists($enum)) {
            $dataValue = $context->getDataValue();

            if (is_string($dataValue) || is_numeric($dataValue)) {
                if (!$decorator->byValue) {
                    $casePath = "{$enum}::{$dataValue}";

                    if (defined($casePath)) {
                        $context->setDataTransferObjectValue(constant($casePath));
                    }
                } else {
                    $context->setDataTransferObjectValue($enum::tryFrom($context->getDataValue()));
                }
            }
        }

        $context->setValueForHarvestableObject($context->getDataTransferObjectValue());
    }
}