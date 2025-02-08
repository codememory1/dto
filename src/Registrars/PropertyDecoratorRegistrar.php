<?php

namespace Codememory\Dto\Registrars;

use Codememory\Dto\Decorators\Property;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorHandlerInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorRegistrarInterface;

class PropertyDecoratorRegistrar implements PropertyDecoratorRegistrarInterface
{
    private array $handlers = [];

    public function __construct()
    {
        $this->registerHandler(new Property\SymfonyValidationHandler());
        $this->registerHandler(new Property\ToEnumHandler());
        $this->registerHandler(new Property\NestedHandler());
    }

    public function registerHandler(DecoratorHandlerInterface $handler): static
    {
        $this->handlers[$handler::class] = $handler;

        return $this;
    }

    public function existsHandler(string $handlerClassName): bool
    {
        return array_key_exists($handlerClassName, $this->handlers);
    }

    public function getHandler(string $handlerClassName): ?PropertyDecoratorHandlerInterface
    {
        return $this->handlers[$handlerClassName] ?? null;
    }
}