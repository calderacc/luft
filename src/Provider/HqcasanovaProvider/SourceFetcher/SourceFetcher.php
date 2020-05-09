<?php declare(strict_types=1);

namespace App\Provider\HqcasanovaProvider\SourceFetcher;

use App\Producer\Value\ValueProducerInterface;
use App\Provider\HqcasanovaProvider\SourceFetcher\Parser\JsonParser;
use App\Provider\HqcasanovaProvider\StationLoader\HqcasanovaStationLoader;
use Curl\Curl;

class SourceFetcher
{
    protected Curl $curl;

    protected JsonParser $jsonParser;

    protected ValueProducerInterface $valueProducer;

    public function __construct(HqcasanovaStationLoader $stationLoader, JsonParser $jsonParser, ValueProducerInterface $valueProducer)
    {
        $this->stationLoader = $stationLoader;
        $this->jsonParser = $jsonParser;
        $this->valueProduer = $valueProducer;

        $this->curl = new Curl();
    }

    public function fetch(): void
    {
        $response = $this->query();

        $valueList = $this->jsonParser->parse($response);

        foreach ($valueList as $value) {
            $this->valueProducer->publish($value);
        }
    }

    protected function query(): string
    {
        $this->curl->get('http://hqcasanova.com/co2/?callback=process');

        return $this->curl->response;
    }
}
