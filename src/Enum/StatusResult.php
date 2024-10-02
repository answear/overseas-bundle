<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Enum;

enum StatusResult: int
{
    case Ok = 0;
    case Error = 1;
    case ValidationFailed = 2;
}
