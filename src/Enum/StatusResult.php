<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Enum;

use MabeEnum\Enum;
use MabeEnum\EnumSerializableTrait;

class StatusResult extends Enum implements \Serializable
{
    use EnumSerializableTrait;

    public const OK = 0;
    public const ERROR = 1;
    public const VALIDATION_FAILED = 2;

    public static function validationFailed(): self
    {
        return static::get(static::VALIDATION_FAILED);
    }

    public static function error(): self
    {
        return static::get(static::ERROR);
    }

    public static function ok(): self
    {
        return static::get(static::OK);
    }
}
