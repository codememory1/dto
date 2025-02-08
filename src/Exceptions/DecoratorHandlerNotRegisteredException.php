<?php

namespace Codememory\Dto\Exceptions;

use function sprintf;
use Throwable;

class DecoratorHandlerNotRegisteredException extends DataTransferObjectException
{
    public function __construct(
        string $dataTransferObjectClassName,
        string $decoratorClassName,
        string $decoratorHandlerClassName,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $dataTransferObjectClassName,
            sprintf(
                'The "%s" handler for the "%s" decorator is not registered.',
                $decoratorHandlerClassName,
                $decoratorClassName
            ),
            $code,
            $previous
        );
    }
}