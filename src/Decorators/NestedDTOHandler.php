<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Exceptions\DataTransferObjectNotFoundException;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;

final class NestedDTOHandler implements DecoratorHandlerInterface
{
    /**
     * @param NestedDTO $decorator
     *
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        if (!class_exists($decorator->dto)) {
            throw new DataTransferObjectNotFoundException($decorator->dto);
        }

        $context->setValue($context->getManager()->create($decorator->dto, $context->getValue()));
    }
}