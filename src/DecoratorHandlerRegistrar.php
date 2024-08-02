<?php

namespace Codememory\Dto;

use Codememory\Dto\Exceptions\DecoratorHandlerNotRegisteredException;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorHandlerRegistrarInterface;

class DecoratorHandlerRegistrar implements DecoratorHandlerRegistrarInterface
{
    /**
     * @var array<int, DecoratorHandlerInterface>
     */
    protected array $handlers = [];

    public function __construct()
    {
        $this->register(new Decorators\CallbackHandler());
        $this->register(new Decorators\ExpectArrayHandler());
        $this->register(new Decorators\ExpectMultiArrayHandler());
        $this->register(new Decorators\ExpectOneDimensionalArrayHandler());
        $this->register(new Decorators\IgnoreSetterCallForHarvestableObjectHandler());
        $this->register(new Decorators\NestedDTOHandler());
        $this->register(new Decorators\PrefixSetterMethodForHarvestableObjectHandler());
        $this->register(new Decorators\SetterMethodForHarvestableObjectHandler());
        $this->register(new Decorators\ToEnumHandler());
        $this->register(new Decorators\ToEnumListHandler());
        $this->register(new Decorators\ToTypeHandler());
        $this->register(new Decorators\ValidationHandler());
        $this->register(new Decorators\XSSHandler());
        $this->register(new Decorators\DynamicValidationHandler());
        $this->register(new Decorators\DynamicDecoratorsHandler());
    }

    public function register(DecoratorHandlerInterface $handler): DecoratorHandlerRegistrarInterface
    {
        if (!array_key_exists($handler::class, $this->handlers)) {
            $this->handlers[$handler::class] = $handler;
        }

        return $this;
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @throws DecoratorHandlerNotRegisteredException
     */
    public function getHandler(string $namespace): DecoratorHandlerInterface
    {
        return $this->handlers[$namespace] ?? throw new DecoratorHandlerNotRegisteredException($namespace);
    }
}