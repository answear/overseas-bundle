<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Exception;

use Answear\OverseasBundle\Response\ResponseInterface;

class BadRequestException extends \RuntimeException
{
    private ResponseInterface $response;

    public function __construct(string $message, ResponseInterface $response, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
