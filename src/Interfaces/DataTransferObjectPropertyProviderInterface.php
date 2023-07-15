<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\Reflectors\ClassReflector;
use Codememory\Reflection\Reflectors\PropertyReflector;

interface DataTransferObjectPropertyProviderInterface
{
    /**
     * @return array<int, PropertyReflector>
     */
    public function getProperties(ClassReflector $classReflector): array;
}