<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Dto\VO\PropertyMetadata;

interface PropertyGrouperInterface
{
    /**
     * @return array<string, array<string, PropertyMetadata>>
     */
    public function groupProperties(ClassExecutionContextInterface $classExecutionContext): array;
}