<?php

namespace Codememory\Dto\Decorators\Property;

use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorHandlerInterface;
use Codememory\Dto\Interfaces\PropertyExecutionContextInterface;

class SymfonyValidationHandler implements PropertyDecoratorHandlerInterface
{
    public const string METADATA_KEY = '__symfony_constraints';

    /**
     * @param SymfonyValidation $decorator
     */
    public function process(DecoratorInterface $decorator, PropertyExecutionContextInterface $executionContext): void
    {
        $metadata = $executionContext->getClassExecutionContext()->getMetadata();
        $inputName = $executionContext->getInputName();

        if (!array_key_exists(self::METADATA_KEY, $metadata)) {
            $metadata[self::METADATA_KEY] = [];
        }

        if (!array_key_exists($inputName, $metadata[self::METADATA_KEY])) {
            $metadata[self::METADATA_KEY][$inputName] = [];
        }

        $metadata[self::METADATA_KEY][$inputName] += $decorator->constraints;

        $executionContext->getClassExecutionContext()->setMetadata($metadata);
    }
}