<?php

namespace Codememory\Dto\Interfaces;

interface PropertyExecutionContextFactoryInterface
{
    public function create(ClassExecutionContextInterface $classExecutionContext, PropertyWrapperInterface $propertyWrapper): PropertyExecutionContextInterface;
}