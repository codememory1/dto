<?php

namespace Codememory\Dto\Interfaces;

interface PropertyDecoratorInterface extends DecoratorInterface
{
    public function getType(): string;
}