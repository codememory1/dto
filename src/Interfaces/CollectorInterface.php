<?php

namespace Codememory\Dto\Interfaces;

use Codememory\Dto\DataTransferControl;

interface CollectorInterface
{
    public function collect(DataTransferControl $dataTransferControl): void;
}