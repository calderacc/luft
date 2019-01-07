<?php declare(strict_types=1);

namespace App\Pollution\DataRetriever;

use App\Entity\Data;
use App\Entity\Station;
use Caldera\GeoBasic\Coord\CoordInterface;
use FOS\ElasticaBundle\Finder\FinderInterface;

class TweakedElasticDataRetriever implements TweakedElasticDataRetrieverInterface
{
    /** @var FinderInterface $dataFinder */
    protected $dataFinder;

    public function __construct(FinderInterface $dataFinder)
    {
        $this->dataFinder = $dataFinder;
    }

    public function retrieveDataForCoord(CoordInterface $coord, int $pollutantId, \DateTime $fromDateTime = null, \DateInterval $dateInterval = null, string $order = 'DESC', float $maxDistance = 20.0, int $maxResults): array
    {
        $stationGeoQuery = new \Elastica\Query\GeoDistance('pin', [
            'lat' => $coord->getLatitude(),
            'lon' => $coord->getLongitude(),
        ],
            sprintf('%fkm', $maxDistance));

        $stationQuery = new \Elastica\Query\Nested();
        $stationQuery->setPath('station');
        $stationQuery->setQuery($stationGeoQuery);

        $pollutantQuery = new \Elastica\Query\Term(['pollutant' => $pollutantId]);

        $boolQuery = new \Elastica\Query\BoolQuery();
        $boolQuery
            ->addMust($pollutantQuery)
            ->addMust($stationQuery);

        if ($fromDateTime && $dateInterval) {
            $untilDateTime = clone $fromDateTime;
            $untilDateTime->add($dateInterval);

            $dateTimeQuery = new \Elastica\Query\Range('dateTime', [
                'gt' => $fromDateTime->format('Y-m-d H:i:s'),
                'lte' => $untilDateTime->format('Y-m-d H:i:s'),
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ]);

            $boolQuery->addMust($dateTimeQuery);
        }

        $query = new \Elastica\Query($boolQuery);
        $query
            ->setSort(['dateTime' => ['order' => strtolower($order)]])
            ->setSize($maxResults);

        return $this->dataFinder->find($query);
    }
}
