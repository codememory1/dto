<?php

namespace Codememory\Dto\Exceptions;

use Exception;
use Throwable;

final class ConstraintNotFoundException extends Exception
{
    public function __construct(string $collector, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Collector {$collector} not found", $code, $previous);
    }
}