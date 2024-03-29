<?php

namespace Codememory\Dto\Interfaces;

interface DecoratorHandlerRegistrarInterface
{
    public function register(DecoratorHandlerInterface $handler): self;

    public function getHandlers(): array;

    public function getHandler(string $namespace): DecoratorHandlerInterface;
}