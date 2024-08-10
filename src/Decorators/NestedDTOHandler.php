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

        $DTOClassName = $decorator->dto ?: $context->getProperty()->getType()->getName();

        if ($decorator->dto) {
            $DTOs = [];

            foreach ($this->getValue($decorator, $context->getValue()) as $value) {
                $DTOs[] = $context->getManager()->create($DTOClassName, $value);
            }

            $context->setValue($DTOs);
        } else {
            $context->setValue($context->getManager()->create($DTOClassName, $this->getValue($decorator, $context->getValue())));
        }
    }

    private function getValue(NestedDTO $decorator, array $value): array
    {
        return null === $decorator->fromKey ? $value : $value[$decorator->fromKey];
    }
}