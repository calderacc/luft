<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Data;
use AppBundle\Entity\Station;
use AppBundle\Pollution\Box\Box;
use AppBundle\Pollution\Pollutant\CO;
use AppBundle\Pollution\Pollutant\NO2;
use AppBundle\Pollution\Pollutant\O3;
use AppBundle\Pollution\Pollutant\PM10;
use AppBundle\Pollution\Pollutant\PollutantInterface;
use AppBundle\Pollution\Pollutant\SO2;
use AppBundle\Pollution\PollutionDataFactory\PollutionDataFactory;
use AppBundle\Repository\DataRepository;
use Caldera\GeoBasic\Coord\Coord;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController extends Controller
{
    protected function getCoordByRequest(Request $request): ?Coord
    {
        $latitude = $request->query->get('latitude');
        $longitude = $request->query->get('longitude');
        $zipCode = $request->query->get('zip');

        if (!$latitude && !$longitude && $zipCode) {
            $zip = $this->getDoctrine()->getRepository('AppBundle:Zip')->findOneByZip($zipCode);

            return $zip;
        }

        if ($latitude && $longitude && !$zipCode) {
            $coord = new Coord(
                $latitude,
                $longitude
            );

            return $coord;
        }

        return null;
    }



    protected function getPollutionDataFactory(): PollutionDataFactory
    {
        return $this->get('AppBundle\Pollution\PollutionDataFactory\PollutionDataFactory');
    }
}
