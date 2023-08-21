<?php

namespace Codememory\Dto\Decorators;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
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

final class XSSHandler implements DecoratorHandlerInterface
{
    /**
     * @param XSS $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $dataValue = $context->getDataValue();
        $validValue = null;

        if (is_string($dataValue)) {
            if ($this->isJson($dataValue)) {
                $validValue = json_encode($this->arrayFilter(json_decode($dataValue, true)));
            } else {
                $validValue = $this->filter($dataValue);
            }
        } else if (is_array($dataValue)) {
            $validValue = $this->arrayFilter($dataValue);
        }

        if (null !== $validValue) {
            $context->setDataTransferObjectValue($validValue);
            $context->setValueForHarvestableObject($validValue);
        }
    }

    private function isJson(string $value): bool
    {
        try {
            return is_array(json_decode($value, true, flags: JSON_THROW_ON_ERROR));
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