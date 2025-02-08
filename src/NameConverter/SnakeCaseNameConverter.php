<?php

namespace Codememory\Dto\NameConverter;

use Codememory\Dto\Interfaces\NameConverterInterface;

class SnakeCaseNameConverter implements NameConverterInterface
{
    public function propertyNameToDataKey(string $propertyName): string
    {
        return mb_strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($propertyName)));
    }

    public function dataKeyToPropertyName(string $key): string
    {
        return lcfirst(str_replace('_', '', ucwords($key, '_')));
    }
}