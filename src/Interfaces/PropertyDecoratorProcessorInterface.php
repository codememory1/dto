<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Dto\VO\PropertyMetadata;

interface PropertyDecoratorProcessorInterface
{
    /**
     * @param array<string, array<string, PropertyMetadata>> $groupedProperties
     *
     * @return array<int, PropertyExecutionContextInterface>
     */
    public function processDecorators(array $groupedProperties, ClassExecutionContextInterface $classExecutionContext, array $data): array;
}