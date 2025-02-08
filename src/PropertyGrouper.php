<?php

namespace Codememory\Dto;

use Codememory\Dto\Interfaces\ClassExecutionContextInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\PropertyExecutionContextFactoryInterface;
use Codememory\Dto\Interfaces\PropertyGrouperInterface;
use Codememory\Dto\VO\PropertyMetadata;
use Codememory\Reflection\Reflectors\AttributeReflector;

readonly class PropertyGrouper implements PropertyGrouperInterface
{
    public function __construct(
        private PropertyExecutionContextFactoryInterface $propertyExecutionContextFactory
    ) {
    }

    public function groupProperties(ClassExecutionContextInterface $classExecutionContext): array
    {
        $groupedProperties = [
            '__no_attributes__' => []
        ];

        foreach ($classExecutionContext->getPropertyWrappers() as $propertyWrapper) {
            $propertyExecutionContext = $this->propertyExecutionContextFactory->create($classExecutionContext, $propertyWrapper);

            if (0 === count($propertyWrapper->getAttributes())) {
                $groupedProperties['__no_attributes__'][$propertyWrapper->getName()] = new PropertyMetadata([], $propertyExecutionContext);
            } else {
                foreach ($propertyWrapper->getAttributes() as $attribute) {
                    $attributeInstance = $attribute instanceof AttributeReflector ? $attribute->getInstance() : $attribute;

                    if ($attributeInstance instanceof DecoratorInterface) {
                        $groupedProperties[$attributeInstance->getType()] ??= [];
                        $groupedProperties[$attributeInstance->getType()][$propertyWrapper->getName()] ??= new PropertyMetadata([], $propertyExecutionContext);

                        $groupedProperties[$attributeInstance->getType()][$propertyWrapper->getName()]->addAttributeInstance($attributeInstance);
                    }
                }
            }
        }

        return $groupedProperties;
    }
}