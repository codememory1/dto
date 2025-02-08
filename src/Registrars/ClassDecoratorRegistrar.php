<?php

namespace Codememory\Dto\Registrars;

use Codememory\Dto\Decorators\Class;
use Codememory\Dto\Interfaces\ClassDecoratorHandlerInterface;
use Codememory\Dto\Interfaces\ClassDecoratorRegistrarInterface;

class ClassDecoratorRegistrar implements ClassDecoratorRegistrarInterface
{
    private array $handlers = [];

    public function __construct()
    {
        $this->registerHandler(new Class\GeneralDecoratorsHandler());
    }

    public function registerHandler(ClassDecoratorHandlerInterface $handler): static
    {
        $this->handlers[$handler::class] = $handler;

        return $this;
    }

    public function existsHandler(string $handlerClassName): bool
    {
        return array_key_exists($handlerClassName, $this->handlers);
    }

    public function getHandler(string $handlerClassName): ?ClassDecoratorHandlerInterface
    {
        return $this->handlers[$handlerClassName] ?? null;
    }
}