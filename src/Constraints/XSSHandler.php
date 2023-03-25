<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use const ENT_DISALLOWED;
use const ENT_HTML401;
use const ENT_HTML5;
use const ENT_QUOTES;
use const ENT_SUBSTITUTE;
use const ENT_XHTML;
use const ENT_XML1;
use function is_array;
use function is_string;
use const JSON_THROW_ON_ERROR;
use JsonException;

final class XSSHandler implements ConstraintHandlerInterface
{
    /**
     * @param XSS $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        $value = $dataTransferControl->getDataValue();

        if (is_string($value)) {
            if ($this->isJson($value)) {
                $dataTransferControl->setValue(json_encode($this->arrayFilter(json_decode($value, true))));
            } else {
                $dataTransferControl->setValue($this->filter($value));
            }
        } else if (is_array($value)) {
            $dataTransferControl->setValue($this->arrayFilter($value));
        }
    }

    private function isJson(string $value): bool
    {
        try {
            json_decode($value, flags: JSON_THROW_ON_ERROR);

            return true;
        } catch (JsonException) {
            return false;
        }
    }

    private function filter(string $value): string
    {
        return htmlspecialchars(
            $value,
            ENT_QUOTES | ENT_HTML401 | ENT_HTML5 | ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_XML1 | ENT_XHTML,
            'UTF-8'
        );
    }

    private function arrayFilter(array $array): array
    {
        foreach ($array as &$value) {
            if (is_string($value)) {
                $value = $this->filter($value);
            } else if (is_array($value)) {
                $value = $this->arrayFilter($value);
            }
        }

        return $array;
    }
}