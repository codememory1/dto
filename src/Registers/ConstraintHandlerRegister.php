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
            new Constraints\IgnoreSetterCallHandler(),
            new Constraints\NestedDTOHandler(),
            new Constraints\ToEnumHandler(),
            new Constraints\ToTypeHandler(),
            new Constraints\ValidationHandler(),
            new Constraints\CallbackHandler(),
            new Constraints\ExpectArrayHandler(),
            new Constraints\ExpectMultiArrayHandler(),
            new Constraints\XSSHandler()
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