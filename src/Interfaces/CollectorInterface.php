<?php

namespace Codememory\Dto\Interfaces;

interface CollectorInterface
{
    public function collect(ExecutionContextInterface $context): void;
}