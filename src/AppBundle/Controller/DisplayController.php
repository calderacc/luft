<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Station;
use Caldera\GeoBasic\Coord\Coord;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DisplayController extends AbstractController
{
    public function stationAction(Request $request, string $stationCode): Response
    {
        $station = $this->getDoctrine()->getRepository(Station::class)->findOneByStationCode($stationCode);

        if (!$station) {
            throw $this->createNotFoundException();
        }

        $boxList = $this->getPollutionDataFactory()->setCoord($station)->createDecoratedBoxList();

        return $this->render(
            'AppBundle:Default:station.html.twig',
            [
                'station' => $station,
                'boxList' => $boxList
            ]
        );
    }

    public function indexAction(Request $request): Response
    {
        $coord = $this->getCoordByRequest($request);

        if (!$coord) {
            return $this->render('AppBundle:Default:select.html.twig');
        }

        $boxList = $this->getPollutionDataFactory()->setCoord($coord)->createDecoratedBoxList();

        if (0 === count($boxList)) {
            return $this->noStationAction($request, $coord);
        }

        return $this->render(
            'AppBundle:Default:display.html.twig',
            [
                'boxList' => $boxList
            ]
        );
    }

    public function noStationAction(Request $request, Coord $coord = null): Response
    {
        if (!$coord) {
            $coord = $this->getCoordByRequest($request);
        }

        $stationList = $this->findNearestStations($coord, 1000);

        return $this->render(
            'AppBundle:Default:nostations.html.twig',
            [
                'stationList' => $stationList
            ]);
    }
}
