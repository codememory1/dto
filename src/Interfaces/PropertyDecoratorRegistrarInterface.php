<?php

namespace Codememory\Dto\Interfaces;

interface PropertyDecoratorRegistrarInterface
{
    public function registerHandler(PropertyDecoratorHandlerInterface $handler): static;

    public function existsHandler(string $handlerClassName): bool;

    public function getHandler(string $handlerClassName): ?PropertyDecoratorHandlerInterface;
}