<?php

namespace Codememory\Dto\Interfaces;

use Throwable;

interface DataTransferObjectExceptionInterface extends Throwable
{
    public function getDataTransferObjectClassName(): string;
}