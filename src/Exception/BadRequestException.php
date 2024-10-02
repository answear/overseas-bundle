<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Exception;

use Answear\OverseasBundle\Enum\StatusResult;
use Answear\OverseasBundle\Response\ResponseInterface;

class BadRequestException extends \RuntimeException
{
    public function __construct(
        public readonly ResponseInterface $response,
        ?\Throwable $previous = null,
    ) {
        $message = 'Error occurs.';
        if (StatusResult::ValidationFailed === $response->getStatus()) {
            $message = 'Validation failed.';
        }

        parent::__construct($message, 0, $previous);
    }
}
