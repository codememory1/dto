<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Reflection\Reflectors\PropertyReflector;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use function is_array;
use const JSON_ERROR_NONE;

final class ToTypeHandler implements DecoratorHandlerInterface
{
    /**
     * @param ToType $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $property = $context->getProperty();
        $value = $context->getDataTransferObjectValue();

        if (null === $value || (is_string($value) && 1 === preg_match('/^\s*$/', $value)) && $property->getType()->allowNullable()) {
            $this->setValue($decorator, $context, null);
        } else {
            if ($this->isType($decorator, $property, 'string')) {
                $this->setValue($decorator, $context, $this->toString($value));
            } else if ($this->isType($decorator, $property, 'int')) {
                $this->setValue($decorator, $context, $this->toInteger($value));
            } else if ($this->isType($decorator, $property, 'float')) {
                $this->setValue($decorator, $context, $this->toFloat($value));
            } else if ($this->isType($decorator, $property, 'bool')) {
                $this->setValue($decorator, $context, $this->toBoolean($value));
            } else if ($this->isType($decorator, $property, 'array')) {
                $this->setValue($decorator, $context, $this->toArray($value));
            } else if ($this->isType($decorator, $property, DateTimeInterface::class)) {
                $this->setValue($decorator, $context, $this->toDateTime($value));
            } else {
                $this->setValue($decorator, $context, $value);
            }
        }
    }

    private function setValue(ToType $decorator, ExecutionContextInterface $context, mixed $value): void
    {
        if ($decorator->onlyData) {
            $context->setDataValue($value);
        } else {
            $context->setDataTransferObjectValue($value);
            $context->setValueForHarvestableObject($value);
        }
    }

    private function isType(ToType $decorator, PropertyReflector $property, string $expectedType): bool
    {
        return $expectedType === $decorator->type || $expectedType === $property->getType()->getName();
    }

    private function toArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (null === $value) {
            return [];
        }

        $value = json_decode($value, true);

        if (empty($value) || JSON_ERROR_NONE !== json_last_error() || !is_array($value)) {
            return [];
        }

        return $value;
    }

    private function toString(mixed $value): string
    {
        if (is_array($value)) {
            return trim(json_encode($value));
        }

        return trim((string) $value);
    }

    private function toInteger(mixed $value): int
    {
        if (is_array($value)) {
            return count($value);
        }

        return (int) $value;
    }

    private function toFloat(mixed $value): float
    {
        return (float) $value;
    }

    private function toBoolean(mixed $value): bool
    {
        return 1 === $value || '1' === $value || true === $value || 'true' === $value;
    }

    private function toDateTime(mixed $value): ?DateTimeInterface
    {
        try {
            return new DateTimeImmutable($value);
        } catch (Exception) {
            return null;
        }
    }
}