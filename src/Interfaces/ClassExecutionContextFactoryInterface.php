<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\Reflectors\ClassReflector;

interface ClassExecutionContextFactoryInterface
{
    public function create(DataTransferObjectManagerInterface $manager, ClassReflector $reflector, array $data): ClassExecutionContextInterface;
}