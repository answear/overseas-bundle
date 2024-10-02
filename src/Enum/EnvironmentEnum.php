<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Enum;

enum EnvironmentEnum: string
{
    case Prod = 'prod';
    case Test = 'test';

    /**
     * @return string[]
     */
    public static function getValues(): array
    {
        return [
            self::Prod->value,
            self::Test->value,
        ];
    }
}
