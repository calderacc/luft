<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\City;
use App\Geocoding\CityGuesserInterface;
use App\Geocoding\Query\GeoQueryInterface;
use App\Pollution\PollutionDataFactory\PollutionDataFactory;
use App\SeoPage\SeoPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DisplayController extends AbstractController
{
    public function indexAction(Request $request, SeoPage $seoPage, GeoQueryInterface $geoQuery, PollutionDataFactory $pollutionDataFactory, CityGuesserInterface $cityGuesser): Response
    {
        $coord = $this->getCoordByRequest($request, $geoQuery);

        if (!$coord) {
            return $this->render('Default/select.html.twig');
        }

        $boxList = $pollutionDataFactory->setCoord($coord)->createDecoratedBoxList();

        if (0 === count($boxList)) {
            return $this->noStationAction();
        }

        $cityName = $cityGuesser->guess($coord);

        if ($cityName) {
            $seoPage->setTitle(sprintf('Aktuelle Luftmesswerte aus %s', $cityName));
            $city = $this->findCityForName($cityName);
        } else {
            $seoPage->setTitle(sprintf('Aktuelle Luftmesswerte aus deiner Umgebung'));
            $city = null;
        }

        return $this->render('Default/display.html.twig', [
            'boxList' => $boxList,
            'cityName' => $cityName,
            'coord' => $coord,
            'city' => $city,
        ]);
    }

    public function noStationAction(): Response
    {
        return $this->render('Default/no_stations.html.twig');
    }

    protected function findCityForName(string $cityName): ?City
    {
        return $this->getDoctrine()->getRepository(City::class)->findOneByName($cityName);
    }
}
