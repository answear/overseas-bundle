<?php

declare(strict_types=1);

namespace Answear\OverseasBundle\Response\DTO;

use Webmozart\Assert\Assert;

class Coordinates
{
    public float $latitude;
    public float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        Assert::range($latitude, -90, 90);
        Assert::range($longitude, -180, 180);

        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}
