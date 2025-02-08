<?php

namespace Codememory\Dto\Factory;

use Codememory\Dto\Interfaces\PropertyWrapperFactoryInterface;
use Codememory\Dto\Interfaces\PropertyWrapperInterface;
use Codememory\Dto\Wrappers\PropertyWrapper;
use Codememory\Reflection\Reflectors\PropertyReflector;

class PropertyWrapperFactory implements PropertyWrapperFactoryInterface
{
    public function create(PropertyReflector $propertyReflector): PropertyWrapperInterface
    {
        return new PropertyWrapper($propertyReflector);
    }
}