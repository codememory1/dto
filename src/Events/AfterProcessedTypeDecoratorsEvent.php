<?php

namespace Codememory\Dto\Events;

use Codememory\Dto\Interfaces\ClassExecutionContextInterface;

class AfterProcessedTypeDecoratorsEvent
{
    public function __construct(
        public readonly ClassExecutionContextInterface $classExecutionContext,
        public string $type,
        public array $data
    ) {
    }
}