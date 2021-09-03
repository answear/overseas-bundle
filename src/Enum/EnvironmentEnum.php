<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Enum;

use MabeEnum\Enum;
use MabeEnum\EnumSerializableTrait;

class EnvironmentEnum extends Enum implements \Serializable
{
    use EnumSerializableTrait;

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
