<?php

namespace Codememory\Dto\Provider;

use function call_user_func;
use Closure;
use Codememory\Dto\AbstractDataTransferObject;
use Codememory\Dto\Interfaces\DataTransferObjectPropertyProviderInterface;
use Codememory\Reflection\Reflectors\ClassReflector;
use ReflectionProperty;

final class DataTransferObjectPublicPropertyProvider implements DataTransferObjectPropertyProviderInterface
{
    private ?Closure $extension = null;

    public function getProperties(ClassReflector $classReflector): array
    {
        $properties = $classReflector->getPropertiesIncludingParent([AbstractDataTransferObject::class], ReflectionProperty::IS_PUBLIC);

        if (null !== $this->extension) {
            $properties = call_user_func($this->extension, $properties);
        }

        return $properties;
    }

    public function setExtension(callable $callback): DataTransferObjectPropertyProviderInterface
    {
        $this->extension = $callback;

        return $this;
    }
}