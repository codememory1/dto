<?php

namespace Codememory\Dto\Factory;

use Codememory\Dto\Context\PropertyExecutionContext;
use Codememory\Dto\Interfaces\ClassExecutionContextInterface;
use Codememory\Dto\Interfaces\NameConverterInterface;
use Codememory\Dto\Interfaces\PropertyExecutionContextFactoryInterface;
use Codememory\Dto\Interfaces\PropertyExecutionContextInterface;
use Codememory\Dto\Interfaces\PropertyWrapperInterface;

readonly class PropertyExecutionContextFactory implements PropertyExecutionContextFactoryInterface
{
    public function __construct(
        private NameConverterInterface $nameConverter
    ) {
    }

    public function create(ClassExecutionContextInterface $classExecutionContext, PropertyWrapperInterface $propertyWrapper): PropertyExecutionContextInterface
    {
        return new PropertyExecutionContext($classExecutionContext, $propertyWrapper, $this->nameConverter);
    }
}