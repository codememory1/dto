<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Exceptions\MethodNotFoundException;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Dto\Storage\SymfonyValidatorStorage;
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

        if (!method_exists($dto, $decorator->callbackName)) {
            throw new MethodNotFoundException($dto->getClassName(), $decorator->callbackName);
        }

        $callbackResult = $dto->getClassName()::{$decorator->callbackName}($context);

        if (!is_array($callbackResult)) {
            throw new RuntimeException("Callback \"{$decorator->callbackName}\" in DTO \"{$dto->getClassName()}\" should return an \"array\"");
        }

        if (!$dto->existStorage(SymfonyValidatorStorage::class)) {
            $dto->createStorage(new SymfonyValidatorStorage());
        }

        $storage = $dto->getStorage(SymfonyValidatorStorage::class);

        $storage->addConstraints($context->getProperty(), $callbackResult);
    }
}