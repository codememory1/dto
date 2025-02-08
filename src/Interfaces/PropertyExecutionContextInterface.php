<?php

namespace Codememory\Dto\Interfaces;

interface PropertyExecutionContextInterface
{
    public function getClassExecutionContext(): ClassExecutionContextInterface;

    public function getPropertyWrapper(): PropertyWrapperInterface;

    public function getInputName(): string;

    public function setInputName(string $inputName): static;

    public function getInputValue(): mixed;

    public function getPropertyValue(): mixed;

    public function setPropertyValue(mixed $value): static;
}