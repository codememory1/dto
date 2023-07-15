<?php

namespace Codememory\Dto\Provider;

use Codememory\Dto\AbstractDataTransferObject;
use Codememory\Dto\Interfaces\DataTransferObjectPropertyProviderInterface;
use Codememory\Reflection\Reflectors\ClassReflector;
use ReflectionProperty;

final class DataTransferObjectPublicPropertyProvider implements DataTransferObjectPropertyProviderInterface
{
    public function getProperties(ClassReflector $classReflector): array
    {
        return $classReflector->getPropertiesIncludingParent([AbstractDataTransferObject::class], ReflectionProperty::IS_PUBLIC);
    }
}