<?php

namespace Codememory\Dto\Registers;

use Codememory\Dto\Exceptions\ConstraintHandlerNotFoundException;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;

final class ConstraintHandlerRegister
{
    private static array $handlers = [];

    public static function register(ConstraintHandlerInterface $constraintHandler): void
    {
        if (!array_key_exists($constraintHandler::class, self::$handlers)) {
            self::$handlers[$constraintHandler::class] = $constraintHandler;
        }
    }

    public static function getHandler(string $namespace): ConstraintHandlerInterface
    {
        return self::$handlers[$namespace] ?? throw new ConstraintHandlerNotFoundException($namespace);
    }
}