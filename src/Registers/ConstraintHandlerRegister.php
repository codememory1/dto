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
        $this->handlers = [
            Constraints\IgnoreSetterCallHandler::class => new Constraints\IgnoreSetterCallHandler(),
            Constraints\NestedDTOHandler::class => new Constraints\NestedDTOHandler(),
            Constraints\ToEnumHandler::class => new Constraints\ToEnumHandler(),
            Constraints\ToTypeHandler::class => new Constraints\ToTypeHandler(),
            Constraints\ValidationHandler::class => new Constraints\ValidationHandler(),
            Constraints\CallbackHandler::class => new Constraints\CallbackHandler(),
            Constraints\ExpectArrayHandler::class => new Constraints\ExpectArrayHandler(),
            Constraints\ExpectMultiArrayHandler::class => new Constraints\ExpectMultiArrayHandler(),
            Constraints\XSSHandler::class => new Constraints\XSSHandler()
        ];
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