<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\City;
use App\Entity\Zip;
use Curl\Curl;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TypeaheadController extends AbstractController
{
    public function prefetchAction(RouterInterface $router): Response
    {
        $cityList = $this->getDoctrine()->getRepository(City::class)->findAll();

        $data = [];

        /** @var City $city */
        foreach ($cityList as $city) {
            $url = $router->generate('show_city', ['citySlug' => $city->getSlug()]);

            $data[] = ['value' => [
                'url' => $url,
                'name' => $city->getName(),
                'icon' => 'university',
            ]];
        }

        return new JsonResponse($data);
    }

    public function searchAction(Request $request, RouterInterface $router): Response
    {
        $queryString = $request->query->get('query');

        $curl = new Curl();
        $curl->get(sprintf('https://photon.komoot.de/api/?q=%s&lang=de', $queryString));

        $features = $curl->response->features;

        //var_dump($features);
        $result = [];

        foreach ($features as $feature) {
            if (!$feature->properties) {
                continue;
            }

            if (!$feature->properties->country || $feature->properties->country !== 'Deutschland') {
                continue;
            }

            $latitude = $feature->geometry->coordinates[1];
            $longitude = $feature->geometry->coordinates[0];
            $url = $router->generate('display', ['latitude' => $latitude, 'longitude' => $longitude]);

            $value = [
                'url' => $url,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'icon' => 'map-marker',
            ];

            if (isset($feature->properties->name)) {
                $value['name'] = $feature->properties->name;

                if (isset($feature->properties->street)) {
                    $value['address'] = $feature->properties->street;
                }
            } elseif (isset($feature->properties->street)) {
                $value['name'] = $feature->properties->street;
            }

            if (isset($feature->properties->city)) {
                $value['city'] = $feature->properties->city;
            }

            if (isset($feature->properties->postcode)) {
                $value['zipCode'] = $feature->properties->postcode;
            }

            if (isset($feature->properties->osm_key) && isset($feature->properties->osm_value)) {
                $osmKey = $feature->properties->osm_key;
                $osmValue = $feature->properties->osm_value;

                if ($osmValue === 'city' || $osmValue === 'suburb') {
                    $value['icon'] = 'city';
                } elseif ($osmValue === 'building' || $osmValue === 'residental') {
                    $value['icon'] = 'road';
                }
            }

            $result[] = [
                'value' => $value
            ];
        }

        return new JsonResponse($result);
    }
}
