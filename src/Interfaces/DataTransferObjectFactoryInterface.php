<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\Reflectors\ClassReflector;

interface DataTransferObjectFactoryInterface
{
    public function create(ClassReflector $classReflector): DataTransferObjectInterface;
}