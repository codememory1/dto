<?php

namespace Codememory\Dto\Events;

use Codememory\Dto\Interfaces\ClassExecutionContextInterface;

class AfterAllProcessedTypeDecoratorsEvent
{
    public function __construct(
        public array $propertyExecutionContexts,
        public ClassExecutionContextInterface $executionContext,
        public array $data
    ) {
    }
}