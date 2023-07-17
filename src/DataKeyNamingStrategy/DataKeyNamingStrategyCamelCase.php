<?php

namespace Codememory\Dto\DataKeyNamingStrategy;

use function call_user_func;
use Closure;
use Codememory\Dto\Interfaces\DataKeyNamingStrategyInterface;
use function Symfony\Component\String\u;

final class DataKeyNamingStrategyCamelCase implements DataKeyNamingStrategyInterface
{
    private ?Closure $extension = null;

    public function convert(string $propertyName): string
    {
        $name = u($propertyName)->camel();

        if (null !== $this->extension) {
            $name = call_user_func($this->extension, $name);
        }

        return $name;
    }

    public function setExtension(callable $callback): DataKeyNamingStrategyInterface
    {
        $this->extension = $callback;

        return $this;
    }
}