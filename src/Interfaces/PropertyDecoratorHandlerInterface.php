<?php

namespace Codememory\Dto\Interfaces;

interface PropertyDecoratorHandlerInterface extends DecoratorHandlerInterface
{
    public function process(DecoratorInterface $decorator, PropertyExecutionContextInterface $executionContext): void;
}