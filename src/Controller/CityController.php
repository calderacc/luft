<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\City;
use App\Entity\User;
use App\Pollution\PollutionDataFactory\PollutionDataFactory;
use App\SeoPage\SeoPage;
use Codebird\Codebird;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CityController extends AbstractController
{
    public function showAction(SeoPage $seoPage, PollutionDataFactory $pollutionDataFactory, string $citySlug): Response
    {
        /** @var City $city */
        $city = $this->getDoctrine()->getRepository(City::class)->findOneBySlug($citySlug);

        if (!$city) {
            throw $this->createNotFoundException();
        }

        $seoPage
            ->setTitle(sprintf('Luftmesswerte aus %s: Stickstoffdioxid, Feinstaub und Ozon', $city->getName()))
            ->setDescription(sprintf('Aktuelle Schadstoffwerte aus Luftmessstationen in %s: Stickstoffdioxid, Feinstaub und Ozon', $city->getName()))
        ;

        $stationList = $this->getStationListForCity($city);
        $stationViewModelList = $this->createViewModelListForStationList($pollutionDataFactory, $stationList);

        return $this->render('City/show.html.twig', [
            'city' => $city,
            'stationList' => $stationList,
            'stationBoxList' => $stationViewModelList,
        ]);
    }

    public function twitterAction(Session $session, string $citySlug): Response
    {
        /** @var City $city */
        $city = $this->getDoctrine()->getRepository(City::class)->findOneBySlug($citySlug);

        if (!$city || $city->getUser()) {
            throw $this->createNotFoundException();
        }

        $session->set('twitterCity', $city);

        return $this->render('City/twitter.html.twig', [
            'city' => $city,
        ]);
    }

    public function twitterAuthAction(Session $session, string $citySlug): Response
    {
        /** @var City $city */
        $city = $this->getDoctrine()->getRepository(City::class)->findOneBySlug($citySlug);

        if (!$city) {
            throw $this->createNotFoundException();
        }

        $cb = $this->getCodeBird();

        $callbackUrl = $this->generateUrl('twitter_token', ['citySlug' => $city->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);

        $reply = $cb->oauth_requestToken([
            'oauth_callback' => $callbackUrl,
        ]);

        $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
        $session->set('oauth_token', $reply->oauth_token);
        $session->set('oauth_token_secret', $reply->oauth_token_secret);
        $session->set('oauth_verify', true);

        return new RedirectResponse($cb->oauth_authorize());
    }

    public function twitterTokenAction(Session $session, Request $request, UserInterface $user, string $citySlug): Response
    {
        /** @var City $city */
        $city = $this->getDoctrine()->getRepository(City::class)->findOneBySlug($citySlug);

        if (!$city) {
            throw $this->createNotFoundException();
        }

        $cb = $this->getCodeBird();
        $cb->setToken($session->get('oauth_token'), $session->get('oauth_token_secret'));

        $verifier = $request->query->get('oauth_verifier');

        if ($verifier && $session->has('oauth_verify')) {
            $session->remove('oauth_verify');

            $reply = $cb->oauth_accessToken([
                'oauth_verifier' => $verifier
            ]);

            $this->saveCityAccess($request, $user, $reply);
        }

        $showUrl = $this->generateUrl('twitter_schedule_list', ['citySlug' => $city->getSlug()]);

        return new RedirectResponse($showUrl);
    }

    public function twitterSuccessAction(Request $request, UserInterface $user): Response
    {
        if ($user->getCity()) {
            return $this->redirectToRoute('twitter_schedule_list', ['citySlug' => $user->getCity()->getSlug()]);
        } else {
            return $this->redirectToRoute('display', ['citySlug' => $user->getCity()->getSlug()]);
        }
    }

    protected function getCodeBird(): Codebird
    {
        Codebird::setConsumerKey($this->getParameter('twitter.client_id'), $this->getParameter('twitter.client_secret'));

        return Codebird::getInstance();
    }

    protected function saveCityAccess(Request $request, User $user, \stdClass $reply): User
    {
        $user->setTwitterSecret($reply->oauth_token_secret);

        $this->getDoctrine()->getManager()->flush();

        return $user;
    }
}
