<?php

declare(strict_types=1);

namespace Answear\OverseasBundle;

use Answear\OverseasBundle\Enum\EnvironmentEnum;

class ConfigProvider
{
    private const URL = 'https://api.overseas.hr/';
    private const TEST_URL = 'https://apitest.overseas.hr';

    private EnvironmentEnum $environment;
    private string $apiKey;

    public function __construct(EnvironmentEnum $environment, string $apiKey)
    {
        $this->environment = $environment;
        $this->apiKey = $apiKey;
    }

    public function getUrl(): string
    {
        return EnvironmentEnum::prod()->is($this->environment) ? static::URL : static::TEST_URL;
    }

    public function getEnvironment(): EnvironmentEnum
    {
        return $this->environment;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
