<?php declare(strict_types=1);

namespace App\Provider\EuropeanEnvironmentAgency;

use App\Provider\AbstractProvider;
use App\Provider\EuropeanEnvironmentAgency\SourceFetcher\SourceFetcher;
use App\Provider\EuropeanEnvironmentAgency\StationLoader\EuropeanEnvironmentAgencyStationLoader;

class EuropeanEnvironmentAgencyProvider extends AbstractProvider
{
    const IDENTIFIER = 'eea';

    public function __construct(EuropeanEnvironmentAgencyStationLoader $agencyStationLoader, SourceFetcher $sourceFetcher)
    {
        $this->stationLoader = $agencyStationLoader;
        $this->sourceFetcher = $sourceFetcher;
    }

    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }
}
