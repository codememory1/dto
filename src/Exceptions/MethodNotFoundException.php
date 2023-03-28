<?php

namespace Codememory\Dto\Exceptions;

use Exception;
use Throwable;

final class MethodNotFoundException extends Exception
{
    public function __construct(string $class, string $method, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(sprintf('Method %s not found in class %s', $class, $method), $code, $previous);
    }
}