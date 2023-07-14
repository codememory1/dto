<?php

namespace Codememory\Dto\Exceptions;

use Exception;
use Throwable;

final class DecoratorNotFoundException extends Exception
{
    public function __construct(string $decoratorNamespace, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("The class {$decoratorNamespace} is not a decorator or cannot be found", $code, $previous);
    }
}