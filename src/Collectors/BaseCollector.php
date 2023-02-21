<?php

namespace Codememory\Dto\Collectors;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Exceptions\ConstraintHandlerNotFoundException;
use Codememory\Dto\Interfaces\CollectorInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Codememory\Dto\Registers\ConstraintHandlerRegister;
use ReflectionProperty;
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

        foreach ($dataTransferControl->property->getAttributes() as $attribute) {
            $attributeInstance = $attribute->newInstance();

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

    private function getValueFromData(array $data, ReflectionProperty $property): mixed
    {
        return $data[u($property->getName())->camel()->toString()] ?? null;
    }

    private function getSetterMethodName(ReflectionProperty $property): string
    {
        return u(sprintf('set_%s', $property->getName()))->camel()->toString();
    }
}