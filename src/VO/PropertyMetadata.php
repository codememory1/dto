<?php

namespace Codememory\Dto\VO;

use Codememory\Dto\Interfaces\PropertyExecutionContextInterface;

class PropertyMetadata
{
    public function __construct(
        private array $attributeInstances,
        private readonly PropertyExecutionContextInterface $propertyExecutionContext
    ) {
    }

    public function getAttributeInstances(): array
    {
        return $this->attributeInstances;
    }

    public function addAttributeInstance(object $instance): static
    {
        $this->attributeInstances[] = $instance;

        return $this;
    }

    public function getPropertyExecutionContext(): PropertyExecutionContextInterface
    {
        return $this->propertyExecutionContext;
    }
}