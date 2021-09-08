<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response;

use Answear\OverseasBundle\Enum\StatusResult;
use Answear\OverseasBundle\Response\DTO\Error;
use Answear\OverseasBundle\Response\DTO\Validation;

interface ResponseInterface
{
    public function getStatus(): StatusResult;

    public function getError(): ?Error;

    /**
     * @return Validation[]
     */
    public function getValidations(): array;
}
