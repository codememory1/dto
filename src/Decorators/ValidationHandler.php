<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Dto\Storage\SymfonyValidatorStorage;

final class ValidationHandler implements DecoratorHandlerInterface
{
    /**
     * @param Validation $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        if (!$context->getDataTransferObject()->existStorage(SymfonyValidatorStorage::class)) {
            $context->getDataTransferObject()->createStorage(new SymfonyValidatorStorage());
        }

        $storage = $context->getDataTransferObject()->getStorage(SymfonyValidatorStorage::class);

        $storage->addConstraints($context->getProperty(), $decorator->assert);
    }
}