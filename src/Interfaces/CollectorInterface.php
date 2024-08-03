<?php

namespace Codememory\Dto\Interfaces;

interface CollectorInterface
{
    /**
     * @param array<int, DecoratorInterface> $decorators
     */
    public function collect(ExecutionContextInterface $context, array $decorators): void;
}