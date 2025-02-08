<?php

namespace Codememory\Dto\Registrars;

use Codememory\Dto\Interfaces\DecoratorTypeRegistrarInterface;
use Codememory\Dto\Interfaces\MutatingDecoratorTypeInterface;
use Codememory\Dto\Interfaces\NonMutatingDecoratorTypeInterface;
use Codememory\Dto\Interfaces\SymfonyValidationDecoratorTypeInterface;
use LogicException;

class DecoratorTypeRegistrar implements DecoratorTypeRegistrarInterface
{
    private array $types = [];

    public function __construct()
    {
        $this->register(SymfonyValidationDecoratorTypeInterface::class, 0);
        $this->register(NonMutatingDecoratorTypeInterface::class, 1);
        $this->register(MutatingDecoratorTypeInterface::class, 2);
    }

    public function register(string $type, int $priority): static
    {
        if (array_key_exists($type, $this->types)) {
            throw new LogicException("The \"{$type}\" decorator type is already registered.");
        }

        foreach ($this->types as &$existingPriority) {
            if ($existingPriority >= $priority) {
                ++$existingPriority;
            }
        }

        unset($existingPriority);

        $this->types[$type] = $priority;

        asort($this->types);

        return $this;
    }

    public function getAllTypes(): array
    {
        return array_keys($this->types);
    }
}