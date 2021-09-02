<?php

declare(strict_types=1);

namespace Answear\OverseasBundle;

class ConfigProvider
{
    private const URL = 'https://api.overseas.hr/';
    private const TEST_URL = 'https://apitest.overseas.hr';

    public function getUrl(): string
    {
        return static::URL;
    }

    public function getTestUrl(): string
    {
        return static::TEST_URL;
    }
}
