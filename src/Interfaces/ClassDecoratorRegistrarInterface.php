<?php

namespace Codememory\Dto\Interfaces;

interface ClassDecoratorRegistrarInterface
{
    public function registerHandler(ClassDecoratorHandlerInterface $handler): static;

    public function existsHandler(string $handlerClassName): bool;

    public function getHandler(string $handlerClassName): ?ClassDecoratorHandlerInterface;
}