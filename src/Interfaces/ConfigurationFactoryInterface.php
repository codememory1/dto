<?php

namespace Codememory\Dto\Interfaces;

interface ConfigurationFactoryInterface
{
    public function createConfiguration(): ConfigurationInterface;
}