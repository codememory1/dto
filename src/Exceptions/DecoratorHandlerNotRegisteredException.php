<?php

namespace Codememory\Dto\Exceptions;

use Exception;
use Throwable;

final class DecoratorHandlerNotRegisteredException extends Exception
{
    public function __construct(string $decoratorHandlerNamespace, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("The decorator handler {$decoratorHandlerNamespace} is not registered in the DataTransfer configuration", $code, $previous);
    }
}