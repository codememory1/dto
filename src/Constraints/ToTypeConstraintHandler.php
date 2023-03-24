<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use function is_array;
use function is_string;
use JetBrains\PhpStorm\Pure;
use const JSON_ERROR_NONE;
use ReflectionProperty;

final class ToTypeConstraintHandler implements ConstraintHandlerInterface
{
    private ?ToTypeConstraint $constraint = null;

    /**
     * @param ToTypeConstraint $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $this->constraint = $constraint;

        $value = $dataTransferControl->getDataValue();
        $trimValue = is_string($value) ? trim($value) : $value;

        if ($dataTransferControl->property->getType()->allowsNull() && (null === $value || '' === $trimValue)) {
            $this->setValue($dataTransferControl, $constraint, null);
        } else if ($this->isType($dataTransferControl->property, 'array')) {
            $this->setValue($dataTransferControl, $constraint, $this->toArray($value));
        } else if ($this->isType($dataTransferControl->property, 'string')) {
            $this->setValue($dataTransferControl, $constraint, $this->toString($value));
        } else if ($this->isType($dataTransferControl->property, 'int')) {
            $this->setValue($dataTransferControl, $constraint, $this->toInteger($value));
        } else if ($this->isType($dataTransferControl->property, 'float')) {
            $this->setValue($dataTransferControl, $constraint, $this->toFloat($value));
        } else if ($this->isType($dataTransferControl->property, 'bool')) {
            $this->setValue($dataTransferControl, $constraint, $this->toBoolean($value));
        } else if ($this->isType($dataTransferControl->property, DateTimeInterface::class)) {
            $this->setValue($dataTransferControl, $constraint, $this->toDateTime($value));
        } else {
            $this->setValue($dataTransferControl, $constraint, $value);
        }
    }

    /**
     * @param ToTypeConstraint $constraint
     */
    private function setValue(DataTransferControl $dataTransferControl, ConstraintInterface $constraint, mixed $value): void
    {
        if ($constraint->onlyData) {
            $dataTransferControl->setDataValue($value);
        } else {
            $dataTransferControl->setValue($value);
        }
    }

    #[Pure]
    private function isType(ReflectionProperty $property, string $type): bool
    {
        return $type === $this->constraint->type || $type === $property->getType()->getName();
    }

    private function toArray(mixed $value): array
    {
        if (true === is_array($value)) {
            return $value;
        }

        $value = json_decode($value, true);

        if (true === empty($value) || JSON_ERROR_NONE !== json_last_error()) {
            return [];
        }

        return $value;
    }

    private function toString(mixed $value): string
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        return (string) $value;
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