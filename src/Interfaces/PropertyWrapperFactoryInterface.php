<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\Reflectors\PropertyReflector;

interface PropertyWrapperFactoryInterface
{
    public function create(PropertyReflector $propertyReflector): PropertyWrapperInterface;
}