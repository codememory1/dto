<?php

namespace Codememory\Dto\Collectors;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Exceptions\ConstraintHandlerNotFoundException;
use Codememory\Dto\Interfaces\CollectorInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Codememory\Dto\Registers\ConstraintHandlerRegister;
use Codememory\Reflection\Reflectors\AttributeReflector;
use Codememory\Reflection\Reflectors\PropertyReflector;
use function Symfony\Component\String\u;

final class BaseCollector implements CollectorInterface
{
    public function collect(DataTransferControl $dataTransferControl): void
    {
        $dataTransferControl->setDataValue($this->getValueFromData($dataTransferControl->data, $dataTransferControl->property));
        $dataTransferControl->setSetterMethodNameToObject($this->getSetterMethodName($dataTransferControl->property));
        $dataTransferControl->setDataTransferValue($dataTransferControl->getDataValue());
        $dataTransferControl->setObjectValue($dataTransferControl->getDataTransferValue());
        $dataTransferControl->setIsSkipProperty(false);
        $dataTransferControl->setIsIgnoreSetterCall(false);
        $dataTransferControl->setDataKey(u($dataTransferControl->property->getName())->snake()->toString());

        /** @var AttributeReflector[] $attributes */
        $attributes = [
            ...$dataTransferControl->dataTransfer->getReflector()->getAttributes(),
            ...$dataTransferControl->property?->getAttributes() ?: []
        ];

        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->getInstance();

            if ($attributeInstance instanceof ConstraintInterface) {
                $this->constraintHandler($attributeInstance, $dataTransferControl);

                if ($dataTransferControl->isSkipProperty()) {
                    break;
                }
            }
        }
    }

    private function constraintHandler(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        if (!class_exists($constraint->getHandler())) {
            throw new ConstraintHandlerNotFoundException($constraint->getHandler());
        }

        ConstraintHandlerRegister::getHandler($constraint->getHandler())->handle($constraint, $dataTransferControl);
    }

    private function getValueFromData(array $data, PropertyReflector $property): mixed
    {
        return $data[u($property->getName())->snake()->toString()] ?? null;
    }

    private function getSetterMethodName(PropertyReflector $property): string
    {
        return u(sprintf('set_%s', $property->getName()))->camel()->toString();
    }
}