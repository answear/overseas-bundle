<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Request;

interface RequestInterface
{
    public function getEndpoint(): string;

    public function getMethod(): string;
}
