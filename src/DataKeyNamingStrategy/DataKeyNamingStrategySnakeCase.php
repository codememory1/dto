<?php

namespace Codememory\Dto\DataKeyNamingStrategy;

use Codememory\Dto\Interfaces\DataKeyNamingStrategyInterface;
use function Symfony\Component\String\u;

final class DataKeyNamingStrategySnakeCase implements DataKeyNamingStrategyInterface
{
    public function convert(string $propertyName): string
    {
        return u($propertyName)->snake();
    }
}