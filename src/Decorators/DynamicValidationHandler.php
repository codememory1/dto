<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Exceptions\MethodNotFoundException;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use function is_array;
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
        $dto = $context->getDataTransferObject();
        $dtoNamespace = $dto::class;

        if (!method_exists($dto, $decorator->callbackName)) {
            throw new MethodNotFoundException($dtoNamespace, $decorator->callbackName);
        }

        $callbackResult = $dto->getClassReflector()->getMethodByName($decorator->callbackName)->invoke($dto);

        if (!is_array($callbackResult)) {
            throw new RuntimeException("Callback \"{$decorator->callbackName}\" in DTO \"{$dtoNamespace}\" should return an \"array\"");
        }

        $context->getDataTransferObject()->addPropertyConstraints(
            $dto,
            $context->getProperty()->getName(),
            $callbackResult
        );
    }
}