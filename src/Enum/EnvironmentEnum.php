<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Enum;

use MabeEnum\Enum;

class EnvironmentEnum extends Enum
{
    public const PROD = 'prod';
    public const TEST = 'test';

    public static function prod(): self
    {
        return static::get(static::PROD);
    }

    public static function test(): self
    {
        return static::get(static::TEST);
    }
}
