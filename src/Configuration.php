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
        // TODO: Зарегестрировать декораторы! Например: $this->registerDecoratorHandler(new Decorator\ToEnumHandler());
    }
}