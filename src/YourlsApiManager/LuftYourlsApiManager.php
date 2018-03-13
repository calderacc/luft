<?php declare(strict_types=1);

namespace App\YourlsApiManager;

use App\Entity\Station;
use App\Entity\TwitterSchedule;
use Caldera\GeoBasic\Coord\Coord;
use Caldera\YourlsApiManager\YourlsApiManager;
use Curl\Curl;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class LuftYourlsApiManager extends YourlsApiManager
{
    /** @var RouterInterface $router */
    protected $router;

    public function __construct(RouterInterface $router, string $apiUrl, string $apiUsername, string $apiPassword)
    {
        $this->router = $router;

        parent::__construct($apiUrl, $apiUsername, $apiPassword);
    }

    public function createPermalinkForTweet(TwitterSchedule $twitterSchedule): string
    {
        $dateTime = new \DateTime();

        if ($twitterSchedule->getStation()) {
            $url = $this->generateUrlForStation($twitterSchedule->getStation(), $dateTime);
        } else {
            $coord = new Coord($twitterSchedule->getLatitude(), $twitterSchedule->getLongitude());

            $url = $this->generateUrlForCoord($coord, $dateTime);
        }

        $permalink = $this->createShorturl($url, $twitterSchedule->getTitle());

        return $permalink;
    }

    protected function generateUrlForStation(Station $station, \DateTimeInterface $dateTime): string
    {
        $url = $this->router->generate('station', ['stationCode' => $station->getStationCode(), 'timestamp' => $dateTime->format('U')], UrlGeneratorInterface::ABSOLUTE_URL);

        $url = str_replace('http://', 'https://', $url);

        return $url;
    }

    protected function generateUrlForCoord(Coord $coord, \DateTimeInterface $dateTime): string
    {
        $url = $this->router->generate('display', ['latitude' => $coord->getLatitude(), 'longitude' => $coord->getLongitude(), 'timestamp' => $dateTime->format('U')], UrlGeneratorInterface::ABSOLUTE_URL);

        $url = str_replace('http://', 'https://', $url);

        return $url;
    }

}
