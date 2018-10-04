<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Station;
use App\Pollution\PollutionDataFactory\PollutionDataFactory;
use App\Pollution\StationFinder\StationFinderInterface;
use App\SeoPage\SeoPage;
use Caldera\GeoBasic\Coord\Coord;
use maxh\Nominatim\Nominatim;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DisplayController extends AbstractController
{
    public function stationAction(SeoPage $seoPage, string $stationCode, PollutionDataFactory $pollutionDataFactory): Response
    {
        /** @var Station $station */
        $station = $this->getDoctrine()->getRepository(Station::class)->findOneByStationCode($stationCode);

        if (!$station) {
            throw $this->createNotFoundException();
        }

        $boxList = $pollutionDataFactory->setCoord($station)->createDecoratedBoxList();

        if ($station->getCity()) {
            $seoPage->setTitle(sprintf('Luftmesswerte für die Station %s in %s: : Stickstoffdioxid, Feinstaub und Ozon', $station->getStationCode(), $station->getCity()->getName()));
        } else {
            $seoPage->setTitle(sprintf('Luftmesswerte für die Station %s: : Stickstoffdioxid, Feinstaub und Ozon', $station->getStationCode()));
        }

        return $this->render('Default/station.html.twig', [
            'station' => $station,
            'boxList' => $boxList,
        ]);
    }

    public function indexAction(Request $request, SeoPage $seoPage, PollutionDataFactory $pollutionDataFactory, StationFinderInterface $stationFinder): Response
    {
        $coord = $this->getCoordByRequest($request);

        if (!$coord) {
            return $this->render('Default/select.html.twig');
        }

        $boxList = $pollutionDataFactory->setCoord($coord)->createDecoratedBoxList();

        if (0 === count($boxList)) {
            return $this->noStationAction($request, $stationFinder, $coord);
        }

        $cityName = $this->getCityNameForCoord($coord);

        if ($cityName) {
            $seoPage->setTitle(sprintf('Aktuelle Luftmesswerte aus %s: : Stickstoffdioxid, Feinstaub und Ozon', $cityName));
        } else {
            $seoPage->setTitle(sprintf('Aktuelle Luftmesswerte aus deiner Umgebung: : Stickstoffdioxid, Feinstaub und Ozon'));
        }

        return $this->render('Default/display.html.twig', [
            'boxList' => $boxList,
        ]);
    }

    public function noStationAction(Request $request, StationFinderInterface $stationFinder, Coord $coord = null): Response
    {
        if (!$coord) {
            $coord = $this->getCoordByRequest($request);
        }

        $stationList = $stationFinder->setCoord($coord)->findNearestStations(1000.0);

        return $this->render('Default/nostations.html.twig', [
            'stationList' => $stationList,
        ]);
    }

    protected function getCityNameForCoord(Coord $coord): ?string
    {
        $url = "http://nominatim.openstreetmap.org/";
        $nominatim = new Nominatim($url);

        $reverse = $nominatim->newReverse()
            ->latlon($coord->getLatitude(), $coord->getLongitude())
        ;

        try {
            $result = $nominatim->find($reverse);

            return $result['address']['city'];
        } catch (\Exception $e) {
            return null;
        }
    }
}
