<?php

namespace Codememory\Dto\Exceptions;

use RuntimeException;
use Throwable;

final class ConstraintHandlerNotFoundException extends RuntimeException
{
    public function __construct(string $namespace, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Constraint handler {$namespace} not found", $code, $previous);
    }
}