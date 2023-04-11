<?php

namespace Codememory\Dto\Registers;

use Codememory\Dto\Exceptions\ConstraintHandlerNotFoundException;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Constraints;

final class ConstraintHandlerRegister
{
    private array $handlers;

    public function __construct()
    {
        $this->register(new Constraints\IgnoreSetterCallHandler());
        $this->register(new Constraints\NestedDTOHandler());
        $this->register(new Constraints\ToEnumHandler());
        $this->register(new Constraints\ToTypeHandler());
        $this->register(new Constraints\ValidationHandler());
        $this->register(new Constraints\ValidationHandler());
        $this->register(new Constraints\CallbackHandler());
        $this->register(new Constraints\ExpectArrayHandler());
        $this->register(new Constraints\ExpectMultiArrayHandler());
        $this->register(new Constraints\XSSHandler());
    }

    public function register(ConstraintHandlerInterface $constraintHandler): void
    {
        if (!array_key_exists($constraintHandler::class, $this->handlers)) {
            $this->handlers[$constraintHandler::class] = $constraintHandler;
        }
    }

    public function getHandler(string $namespace): ConstraintHandlerInterface
    {
        return $this->handlers[$namespace] ?? throw new ConstraintHandlerNotFoundException($namespace);
    }
}