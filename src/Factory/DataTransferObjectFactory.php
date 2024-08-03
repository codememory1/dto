<?php

namespace Codememory\Dto\Factory;

use Codememory\Dto\DataTransferObject;
use Codememory\Dto\Interfaces\DataTransferObjectFactoryInterface;
use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Reflection\Reflectors\ClassReflector;

class DataTransferObjectFactory implements DataTransferObjectFactoryInterface
{
    public function create(ClassReflector $classReflector): DataTransferObjectInterface
    {
        return new DataTransferObject($classReflector);
    }
}