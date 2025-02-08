<?php

namespace Codememory\Dto\Interfaces;

interface ClassDecoratorProcessorInterface
{
    public function processDecorators(ClassExecutionContextInterface $classExecutionContext, array $data): void;
}