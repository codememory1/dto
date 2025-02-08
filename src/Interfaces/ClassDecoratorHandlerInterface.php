<?php

namespace Codememory\Dto\Interfaces;

interface ClassDecoratorHandlerInterface extends DecoratorHandlerInterface
{
    public function process(DecoratorInterface $decorator, ClassExecutionContextInterface $executionContext): void;
}