<?php

namespace Codememory\Dto\Interfaces;

interface DecoratorTypeRegistrarInterface
{
    public function register(string $type, int $priority): static;

    /**
     * @return array<int, string>
     */
    public function getAllTypes(): array;
}