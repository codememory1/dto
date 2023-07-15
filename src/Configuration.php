<?php

namespace Codememory\Dto;

use Codememory\Dto\DataKeyNamingStrategy\DataKeyNamingStrategySnakeCase;
use Codememory\Dto\Exceptions\DecoratorHandlerNotRegisteredException;
use Codememory\Dto\Interfaces\ConfigurationInterface;
use Codememory\Dto\Interfaces\DataKeyNamingStrategyInterface;
use Codememory\Dto\Interfaces\DataTransferObjectPropertyProviderInterface;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Provider\DataTransferObjectPublicPropertyProvider;

class Configuration implements ConfigurationInterface
{
    private ?DataKeyNamingStrategyInterface $dataKeyNamingStrategy = null;
    private ?DataTransferObjectPropertyProviderInterface $dataTransferObjectPropertyProvider = null;
    private array $decoratorHandlers = [];

    public function __construct()
    {
        $this->decoratorHandlersRegistrationWrapper();
    }

    public function getDataKeyNamingStrategy(): DataKeyNamingStrategyInterface
    {
        return $this->dataKeyNamingStrategy ?: new DataKeyNamingStrategySnakeCase();
    }

    public function setDataKeyNamingStrategy(DataKeyNamingStrategyInterface $strategy): ConfigurationInterface
    {
        $this->dataKeyNamingStrategy = $strategy;

        return $this;
    }

    public function getDataTransferObjectPropertyProvider(): DataTransferObjectPropertyProviderInterface
    {
        return $this->dataTransferObjectPropertyProvider ?: new DataTransferObjectPublicPropertyProvider();
    }

    public function setDataTransferObjectPropertyProvider(DataTransferObjectPropertyProviderInterface $provider): self
    {
        $this->dataTransferObjectPropertyProvider = $provider;

        return $this;
    }

    public function getDecoratorHandlers(): array
    {
        return $this->decoratorHandlers;
    }

    /**
     * @throws DecoratorHandlerNotRegisteredException
     */
    public function getDecoratorHandler(string $handlerNamespace): DecoratorHandlerInterface
    {
        return $this->decoratorHandlers[$handlerNamespace] ?? throw new DecoratorHandlerNotRegisteredException($handlerNamespace);
    }

    public function registerDecoratorHandler(DecoratorHandlerInterface $handler): self
    {
        if (!array_key_exists($handler::class, $this->decoratorHandlers)) {
            $this->decoratorHandlers[$handler::class] = $handler;
        }

        return $this;
    }

    private function decoratorHandlersRegistrationWrapper(): void
    {
        $this->registerDecoratorHandler(new Decorators\CallbackHandler());
        $this->registerDecoratorHandler(new Decorators\ExpectArrayHandler());
        $this->registerDecoratorHandler(new Decorators\ExpectMultiArrayHandler());
        $this->registerDecoratorHandler(new Decorators\ExpectOneDimensionalArrayHandler());
        $this->registerDecoratorHandler(new Decorators\IgnoreSetterCallForHarvestableObjectHandler());
        $this->registerDecoratorHandler(new Decorators\NestedDTOHandler());
        $this->registerDecoratorHandler(new Decorators\PrefixSetterMethodForHarvestableObjectHandler());
        $this->registerDecoratorHandler(new Decorators\SetterMethodForHarvestableObjectHandler());
        $this->registerDecoratorHandler(new Decorators\ToEnumHandler());
        $this->registerDecoratorHandler(new Decorators\ToEnumListHandler());
        $this->registerDecoratorHandler(new Decorators\ToTypeHandler());
        $this->registerDecoratorHandler(new Decorators\ValidationHandler());
        $this->registerDecoratorHandler(new Decorators\XSSHandler());
    }
}