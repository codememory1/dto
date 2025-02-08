<?php

namespace Codememory\Dto\Decorators\Class;

use Attribute;
use Codememory\Dto\Interfaces\ClassDecoratorInterface;

#[Attribute(Attribute::TARGET_CLASS)]
class GeneralDecorators implements ClassDecoratorInterface
{
    public function __construct(
        public array $propertyDecorators
    ) {
    }

    public function getHandler(): string
    {
        return GeneralDecoratorsHandler::class;
    }
}