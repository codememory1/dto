<?php

namespace Codememory\Dto\Events;

use Codememory\Dto\Interfaces\ClassExecutionContextInterface;

class BeforeAllProcessedTypeDecoratorsEvent
{
    public function __construct(
        public ClassExecutionContextInterface $executionContext,
        public array $data
    ) {
    }
}