<?php

namespace AppBundle\Pollution\PollutionDataFactory;

use AppBundle\Entity\Data;
use AppBundle\Entity\Station;
use AppBundle\Pollution\Box\Box;
use AppBundle\Pollution\Pollutant\CO;
use AppBundle\Pollution\Pollutant\NO2;
use AppBundle\Pollution\Pollutant\O3;
use AppBundle\Pollution\Pollutant\PM10;
use AppBundle\Pollution\Pollutant\PollutantInterface;
use AppBundle\Pollution\Pollutant\SO2;
use AppBundle\Repository\DataRepository;
use Caldera\GeoBasic\Coord\CoordInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use FOS\ElasticaBundle\Finder\FinderInterface;

class PollutionDataFactory
{
    /**
     * @var Doctrine $doctrine
     */
    protected $doctrine;

    /**
     * @var CoordInterface $coord
     */
    protected $coord;

    /**
     * @var FinderInterface $stationFinder
     */
    protected $stationFinder;

    public function __construct(Doctrine $doctrine, FinderInterface $stationFinder)
    {
        $this->doctrine = $doctrine;
        $this->stationFinder = $stationFinder;
    }

    public function setCoord(CoordInterface $coord): PollutionDataFactory
    {
        $this->coord = $coord;

        return $this;
    }

    public function createDecoratedBoxList(): array
    {
        $stationList = $this->findNearestStations();

        $dataList = $this->getDataListFromStationList($stationList);

        $boxList = $this->getBoxListFromDataList($dataList);

        $boxList = $this->decorateBoxList($boxList);

        return $boxList;
    }

    protected function findNearestStations(): array
    {
        $geoFilter = new \Elastica\Filter\GeoDistance(
            'pin',
            [
                'lat' => $this->coord->getLatitude(),
                'lon' => $this->coord->getLongitude()
            ],
            '20km'
        );

        $filteredQuery = new \Elastica\Query\Filtered(new \Elastica\Query\MatchAll(), $geoFilter);

        $query = new \Elastica\Query($filteredQuery);

        $query->setSize(15);
        $query->setSort(
            [
                '_geo_distance' =>
                    [
                        'pin' =>
                            [
                                'lat' => $this->coord->getLatitude(),
                                'lon' => $this->coord->getLongitude()
                            ],
                        'order' => 'asc',
                        'unit' => 'km'
                    ]
            ]
        );

        $results = $this->stationFinder->find($query);

        return $results;
    }

    protected function getDataListFromStationList(array $stationList): array
    {
        $dataList = [
            PollutantInterface::POLLUTANT_PM10 => null,
            PollutantInterface::POLLUTANT_O3 => null,
            PollutantInterface::POLLUTANT_NO2 => null,
            PollutantInterface::POLLUTANT_SO2 => null,
            PollutantInterface::POLLUTANT_CO => null,
        ];

        foreach ($stationList as $station) {
            foreach ($dataList as $pollutant => $data) {
                if (!$data) {
                    $data = $this->checkStationData($station, $pollutant);

                    if ($data) {
                        $dataList[$pollutant] = $data;
                    }
                }
            }
        }

        return $dataList;
    }

    protected function checkStationData(Station $station, string $pollutant): ?Data
    {
        /** @var DataRepository $repository */
        $repository = $this->doctrine->getRepository(Data::class);

        return $repository->findLatestDataForStationAndPollutant($station, $pollutant);
    }

    protected function getPollutantById(int $pollutantId): PollutantInterface
    {
        switch ($pollutantId) {
            case 1: return new PM10();
            case 2: return new O3();
            case 3: return new NO2();
            case 4: return new SO2();
            case 5: return new CO();
        }
    }

    protected function getBoxListFromDataList(array $dataList): array
    {
        $boxList = [];

        foreach ($dataList as $data) {
            if ($data) {
                $boxList[] = new Box($data);
            }
        }

        return $boxList;
    }

    protected function decorateBoxList(array $boxList): array
    {
        /** @var Box $box */
        foreach ($boxList as $box) {
            $data = $box->getData();

            $pollutant = $this->getPollutantById($data->getPollutant());
            $level = $pollutant->getPollutionLevel()->getLevel($data);

            $box
                ->setStation($data->getStation())
                ->setPollutant($pollutant)
                ->setPollutionLevel($level)
            ;
        }

        return $boxList;
    }
}