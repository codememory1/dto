<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Reflection\ReflectorManager;
use Codememory\Reflection\Reflectors\ClassReflector;

interface DataTransferObjectInterface
{
    public function getCollector(): CollectorInterface;

    public function getConfiguration(): ConfigurationInterface;

    public function getReflectorManager(): ReflectorManager;

    public function getClassReflector(): ClassReflector;

    public function getHarvestableObject(): ?object;

    public function setHarvestableObject(object $object): self;

    public function collect(array $data): self;

    public function recollectHarvestableObject(object $newObject): self;
}