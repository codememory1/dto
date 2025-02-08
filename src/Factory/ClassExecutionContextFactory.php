<?php

namespace Codememory\Dto\Factory;

use Codememory\Dto\Context\ClassExecutionContext;
use Codememory\Dto\Interfaces\ClassExecutionContextFactoryInterface;
use Codememory\Dto\Interfaces\ClassExecutionContextInterface;
use Codememory\Dto\Interfaces\DataTransferObjectManagerInterface;
use Codememory\Dto\Interfaces\PropertyWrapperFactoryInterface;
use Codememory\Reflection\Reflectors\ClassReflector;

readonly class ClassExecutionContextFactory implements ClassExecutionContextFactoryInterface
{
    public function __construct(
        private PropertyWrapperFactoryInterface $propertyWrapperFactory
    ) {
    }

    public function create(DataTransferObjectManagerInterface $manager, ClassReflector $reflector, array $data): ClassExecutionContextInterface
    {
        return new ClassExecutionContext($manager, $this->propertyWrapperFactory, $reflector, $data);
    }
}