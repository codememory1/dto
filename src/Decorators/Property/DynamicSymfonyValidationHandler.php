<?php

declare (strict_types = 1);

namespace Codememory\Dto\Decorators\Property;

use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorHandlerInterface;
use Codememory\Dto\Interfaces\PropertyExecutionContextInterface;
use RuntimeException;

final class DynamicSymfonyValidationHandler implements PropertyDecoratorHandlerInterface
{
    /**
     * @param DynamicSymfonyValidation $decorator
     */
    public function process(DecoratorInterface $decorator, PropertyExecutionContextInterface $executionContext): void
    {
        $className = $this->getClassName($decorator, $executionContext);
        $methodName = $this->getMethodName($decorator);

        if (!method_exists($className, $methodName)) {
            throw new RuntimeException("Method \"{$methodName}\" does not exist in class \"{$className}\".");
        }

        $metadata = $executionContext->getClassExecutionContext()->getMetadata();
        $inputName = $executionContext->getInputName();

        if (!array_key_exists(SymfonyValidationHandler::METADATA_KEY, $metadata)) {
            $metadata[SymfonyValidationHandler::METADATA_KEY] = [];
        }

        if (!array_key_exists($inputName, $metadata[SymfonyValidationHandler::METADATA_KEY])) {
            $metadata[SymfonyValidationHandler::METADATA_KEY][$inputName] = [];
        }

        $metadata[SymfonyValidationHandler::METADATA_KEY][$inputName] += $className::{$methodName}($executionContext->getClassExecutionContext()->getData(), $executionContext);

        $executionContext->getClassExecutionContext()->setMetadata($metadata);
    }

    private function getClassName(DynamicSymfonyValidation $dynamicValidation, PropertyExecutionContextInterface $executionContext): string
    {
        if (is_array($dynamicValidation->callback)) {
            return $dynamicValidation->callback[0];
        }

        return $executionContext->getClassExecutionContext()->getReflector()->getName();
    }

    private function getMethodName(DynamicSymfonyValidation $dynamicValidation): string
    {
        if (is_array($dynamicValidation->callback)) {
            return $dynamicValidation->callback[1];
        }

        return $dynamicValidation->callback;
    }
}