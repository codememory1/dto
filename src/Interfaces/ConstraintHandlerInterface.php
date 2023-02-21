<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Dto\DataTransferControl;

interface ConstraintHandlerInterface
{
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void;
}