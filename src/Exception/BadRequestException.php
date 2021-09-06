<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Exception;

use Answear\OverseasBundle\Enum\StatusResult;
use Answear\OverseasBundle\Response\ResponseInterface;

class BadRequestException extends \RuntimeException
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response, ?\Throwable $previous = null)
    {
        $message = 'Error occurs.';
        if ($response->getStatus()->is(StatusResult::validationFailed())) {
            $message = 'Validation failed.';
        }
        parent::__construct($message, 0, $previous);

        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
