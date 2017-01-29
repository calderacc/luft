<?php

namespace AppBundle\Pollution\Pollutant;

interface PollutantInterface
{
    const POLLUTANT_PM10 = 1;
    const POLLUTANT_O3 = 2;
    const POLLUTANT_NO2 = 3;
    const POLLUTANT_SO2 = 4;
    const POLLUTANT_CO = 5;

    public function getUnit(): string;
    public function getName(): string;
}