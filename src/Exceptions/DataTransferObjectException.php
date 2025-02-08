<?php

namespace Codememory\Dto\Exceptions;

use Codememory\Dto\Interfaces\DataTransferObjectExceptionInterface;
use Exception;
use Throwable;

class DataTransferObjectException extends Exception implements DataTransferObjectExceptionInterface
{
    public function __construct(
        private readonly string $dataTransferObjectClassName,
        string $message,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct("Error {$this->dataTransferObjectClassName}: {$message}", $code, $previous);
    }

    public function getDataTransferObjectClassName(): string
    {
        return $this->dataTransferObjectClassName;
    }
}