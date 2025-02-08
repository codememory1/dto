<?php

namespace Codememory\Dto\Events;

use Codememory\Dto\Interfaces\ClassExecutionContextInterface;

class BeforeProcessedClassDecoratorsEvent
{
    public function __construct(
        public ClassExecutionContextInterface $executionContext,
        public array $data
    ) {
    }
}