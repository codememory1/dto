<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Exceptions\MethodNotFoundException;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use RuntimeException;

final class DynamicValidationHandler implements DecoratorHandlerInterface
{
    /**
     * @param DynamicValidation $decorator
     *
     * @throws MethodNotFoundException
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $dtoNamespace = $context->getDataTransferObject()::class;

        if (!method_exists($context->getDataTransferObject(), $decorator->callbackName)) {
            throw new MethodNotFoundException($dtoNamespace, $decorator->callbackName);
        }

        $callbackResult = $context->getDataTransferObject()->{$decorator->callbackName}();

        if (!is_array($callbackResult)) {
            throw new RuntimeException("Callback \"{$decorator->callbackName}\" in DTO \"$dtoNamespace\" should return an \"array\"");
        }

        $context->getDataTransferObject()->addPropertyConstraints(
            $context->getDataTransferObject(),
            $context->getProperty()->getName(),
            $context->getDataTransferObject()->{$decorator->callbackName}()
        );
    }
}