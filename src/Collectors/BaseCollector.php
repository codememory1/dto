<?php

namespace Codememory\Dto\Collectors;

use Codememory\Dto\Exceptions\DecoratorHandlerNotRegisteredException;
use Codememory\Dto\Interfaces\CollectorInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Reflection\Reflectors\AttributeReflector;

final class BaseCollector implements CollectorInterface
{
    /**
     * @throws DecoratorHandlerNotRegisteredException
     */
    public function collect(ExecutionContextInterface $context): void
    {
        foreach ($this->getAttributes($context) as $attribute) {
            /** @var DecoratorInterface $decorator */
            $decorator = $attribute->getInstance();

            if ($decorator instanceof DecoratorInterface) {
                $this->decoratorHandler($decorator, $context);

                if ($context->isSkippedThisProperty()) {
                    break;
                }
            }
        }
    }

    /**
     * @return array<int, AttributeReflector>
     */
    private function getAttributes(ExecutionContextInterface $context): array
    {
        return [
            ...$context->getDataTransferObject()->getClassReflector()->getAttributes(),
            ...$context->getProperty()->getAttributes()
        ];
    }

    /**
     * @throws DecoratorHandlerNotRegisteredException
     */
    private function decoratorHandler(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        if (!class_exists($decorator->getHandler())) {
            throw new DecoratorHandlerNotRegisteredException($decorator->getHandler());
        }

        $context->getDataTransferObject()
            ->getConfiguration()
            ->getDecoratorHandler($decorator->getHandler())
            ->handle($decorator, $context);
    }
}