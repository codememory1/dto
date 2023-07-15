<?php

namespace Codememory\Dto\Interfaces;

interface DecoratorHandlerInterface
{
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void;
}