<?php

namespace Codememory\Dto\Factory;

use Codememory\Dto\Configuration;
use Codememory\Dto\Interfaces\ConfigurationFactoryInterface;
use Codememory\Dto\Interfaces\ConfigurationInterface;

final class ConfigurationFactory implements ConfigurationFactoryInterface
{
    public function createConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }
}