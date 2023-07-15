<?php

namespace Codememory\Dto\Interfaces;

interface ConfigurationInterface
{
    /**
     * Key naming strategy from property name, to get value from data.
     */
    public function getDataKeyNamingStrategy(): DataKeyNamingStrategyInterface;

    public function setDataKeyNamingStrategy(DataKeyNamingStrategyInterface $strategy): self;

    public function getDataTransferObjectPropertyProvider(): DataTransferObjectPropertyProviderInterface;

    public function setDataTransferObjectPropertyProvider(DataTransferObjectPropertyProviderInterface $provider): self;

    /**
     * @return array<string, DecoratorHandlerInterface>
     */
    public function getDecoratorHandlers(): array;

    public function getDecoratorHandler(string $handlerNamespace): DecoratorHandlerInterface;

    public function registerDecoratorHandler(DecoratorHandlerInterface $handler): self;
}