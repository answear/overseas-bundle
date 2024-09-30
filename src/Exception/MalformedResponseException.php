<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Exception;

use Psr\Http\Message\ResponseInterface;

class MalformedResponseException extends \RuntimeException
{
    public function __construct(
        string $message,
        public readonly ResponseInterface $response,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
