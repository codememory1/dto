<?php

namespace Codememory\Dto\Events;

use Codememory\Dto\Interfaces\ClassExecutionContextInterface;

class AfterProcessedClassDecoratorsEvent
{
    public function __construct(
        public ClassExecutionContextInterface $executionContext,
        public array $data
    ) {
    }
}