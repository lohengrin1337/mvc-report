<?php

namespace App\Controller;

use App\Card\CardDeck;
use App\Card\CardHand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * controller class for api routes
 */
class CardApiController extends AbstractController
{
    /**
     * @var JsonResponse $response
     */
    private JsonResponse $response;



    /**
     * Set new JsonResponse with data
     * Update $this->response
     *
     * @param array $data - data to put in the JsonResponse
     */
    private function setResponse(array $data): void
    {
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        $this->response = $response;
    }



    #[Route("/api/deck", name: "api_deck", methods: ["GET"])]
    public function apiDeck(SessionInterface $session): JsonResponse
    {
        $deck = $session->get("deck") ?? null;
        if (!$deck) {
            $deck = new CardDeck;
        }

        $deck->sort();
        $session->set("deck", $deck);
        $this->setResponse($deck->getAsString());

        return $this->response;
    }



    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ["POST"])]
    public function apiDeckShuffle(SessionInterface $session): JsonResponse
    {
        $deck = $session->get("deck") ?? null;
        if (!$deck) {
            $deck = new CardDeck;
        }

        $deck->shuffle();
        $session->set("deck", $deck);
        $this->setResponse($deck->getAsString());

        return $this->response;
    }
}
