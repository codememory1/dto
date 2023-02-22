<?php

namespace Codememory\Dto\Constraints;

use Codememory\Dto\DataTransferCollection;
use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Symfony\Component\HttpFoundation\Request;

final class AsPatchConstraintHandler implements ConstraintHandlerInterface
{
    public function __construct(
        private readonly Request $request
    ) {
    }

    /**
     * @param AsPatchConstraint $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        if ($this->isPatch() && !$this->keyInRequest($dataTransferControl)) {
            $dataTransferControl->setIsSkipProperty(true);
            $dataTransferControl->setIsIgnoreSetterCall(true);
        } else {
            /** @var DataTransferCollection $collection */
            $collection = $dataTransferControl->dataTransfer->getListDataTransferCollection()[$dataTransferControl->dataTransfer::class];

            $collection->addPropertyValidation($dataTransferControl->property->getName(), $constraint->assert);
        }
    }

    private function isPatch(): bool
    {
        return Request::METHOD_PATCH === $this->request->getMethod();
    }

    private function keyInRequest(DataTransferControl $dataTransferControl): bool
    {
        return array_key_exists($dataTransferControl->getDataKey(), $this->requestData());
    }

    private function requestData(): array
    {
        if ($this->request->isXmlHttpRequest()) {
            return $this->request->toArray();
        }

        $requestData = $this->request->request->all() ?: [];
        $queryData = $this->request->query->all() ?: [];

        return array_merge($requestData, $queryData);
    }
}