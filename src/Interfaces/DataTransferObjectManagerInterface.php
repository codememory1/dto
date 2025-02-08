<?php

namespace Codememory\Dto\Interfaces;

interface DataTransferObjectManagerInterface
{
    public function hydrate(string $dataTransferObjectClassName, array $data): object;
}