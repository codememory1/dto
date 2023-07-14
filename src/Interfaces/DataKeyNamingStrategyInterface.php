<?php

namespace Codememory\Dto\Interfaces;

interface DataKeyNamingStrategyInterface
{
    public function convert(string $propertyName): string;
}