<?php

namespace Codememory\Dto;

use Codememory\Dto\DataKeyNamingStrategy\DataKeyNamingStrategySnakeCase;
use Codememory\Dto\Interfaces\ConfigurationInterface;
use Codememory\Dto\Interfaces\DataKeyNamingStrategyInterface;
use Codememory\Dto\Interfaces\DataTransferObjectPropertyProviderInterface;
use Codememory\Dto\Provider\DataTransferObjectPublicPropertyProvider;

class Configuration implements ConfigurationInterface
{
    protected ?DataKeyNamingStrategyInterface $dataKeyNamingStrategy = null;
    protected ?DataTransferObjectPropertyProviderInterface $dataTransferObjectPropertyProvider = null;

    public function getDataKeyNamingStrategy(): DataKeyNamingStrategyInterface
    {
        return $this->dataKeyNamingStrategy ?: $this->dataKeyNamingStrategy = new DataKeyNamingStrategySnakeCase();
    }

    public function setDataKeyNamingStrategy(DataKeyNamingStrategyInterface $strategy): ConfigurationInterface
    {
        $this->dataKeyNamingStrategy = $strategy;

        return $this;
    }

    public function getDataTransferObjectPropertyProvider(): DataTransferObjectPropertyProviderInterface
    {
        return $this->dataTransferObjectPropertyProvider ?: $this->dataTransferObjectPropertyProvider = new DataTransferObjectPublicPropertyProvider();
    }

    public function setDataTransferObjectPropertyProvider(DataTransferObjectPropertyProviderInterface $provider): self
    {
        $this->dataTransferObjectPropertyProvider = $provider;

        return $this;
    }
}