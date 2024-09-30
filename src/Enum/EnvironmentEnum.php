<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Enum;

enum EnvironmentEnum: string implements \JsonSerializable
{
    case Prod = 'prod';
    case Test = 'test';

    public function jsonSerialize(): string
    {
        return $this->value;
    }

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
