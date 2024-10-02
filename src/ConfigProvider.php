<?php

declare(strict_types=1);

namespace Answear\OverseasBundle;

use Answear\OverseasBundle\Enum\EnvironmentEnum;

class ConfigProvider
{
    private const URL = 'https://api.overseas.hr/';
    private const TEST_URL = 'https://apitest.overseas.hr/';

    private EnvironmentEnum $environment;

    public function __construct(
        string $environment,
        public string $apiKey,
    ) {
        $this->environment = EnvironmentEnum::from($environment);
    }

    public function getUrl(): string
    {
        return EnvironmentEnum::Prod === $this->environment ? self::URL : self::TEST_URL;
    }

    public function getEnvironment(): EnvironmentEnum
    {
        return $this->environment;
    }
}
