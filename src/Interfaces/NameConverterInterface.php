<?php

namespace Codememory\Dto\Interfaces;

interface NameConverterInterface
{
    public function propertyNameToDataKey(string $propertyName): string;

    public function dataKeyToPropertyName(string $key): string;
}