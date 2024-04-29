<?php

namespace App\Controller;

use App\Card\CardDeck;
use App\Card\CardHand;
use App\Card\Card;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * controller class for api routes
 */
class CardApiController extends AbstractController
{
    use JsonResponseTrait;

    #[Route("/api/deck", name: "api_deck", methods: ["GET"])]
    public function apiDeck(SessionInterface $session): JsonResponse
    {
        // $deck = $session->get("deck") ?? null;
        // if (!$deck) {
        //     $deck = new CardDeck(Card::class);
        // }
        $deck = new CardDeck(Card::class);

        $deck->sort();
        $session->set("card_deck", $deck);
        $this->setResponse($deck->getAsString());

        return $this->response;
    }



    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ["POST"])]
    public function apiDeckShuffle(SessionInterface $session): JsonResponse
    {
        // $deck = $session->get("deck") ?? null;
        // if (!$deck) {
        //     $deck = new CardDeck(Card::class);
        // }
        $deck = new CardDeck(Card::class);

        $deck->shuffle();
        $session->set("card_deck", $deck);
        $this->setResponse($deck->getAsString());

        return $this->response;
    }



    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ["POST"])]
    public function apiDeckDraw(
        Request $request,
        SessionInterface $session
    ): JsonResponse {
        $number = $request->request->get("number") ?? 1;
        // $number = $request->request->get("number") ?? null;
        // if (!$number) {
        //     throw new Exception("Antal kort saknades i request (POST) '/api/deck/draw'");
        // }

        $deck = $session->get("card_deck") ?? null;
        if (!$deck) {
            $deck = new CardDeck(Card::class);
        }

        $hand = new CardHand();
        $hand->draw($deck, (int) $number);
        $session->set("card_deck", $deck);

        $data = [
            "hand" => $hand->getAsString(),
            "deckCount" => $deck->getCount(),
        ];

        $this->setResponse($data);

        return $this->response;
    }



    #[Route("/api/deck/deal", name: "api_deck_deal", methods: ["POST"])]
    public function apiDeckDeal(
        Request $request,
        SessionInterface $session
    ): JsonResponse {
        $players = $request->request->get("players") ?? null;
        if (!$players) {
            throw new Exception("Antal spelare saknades i request (POST) '/api/deck/deal'");
        }
        $cards = $request->request->get("cards") ?? null;
        if (!$cards) {
            throw new Exception("Antal kort saknades i request (POST) '/api/deck/deal'");
        }
        $deck = $session->get("deck") ?? null;
        if (!$deck) {
            $deck = new CardDeck(Card::class);
        }

        $data = [];
        for ($i = 1; $i <= $players; $i++) {
            $hand = new CardHand();
            $hand->draw($deck, (int) $cards);
            $data["Player {$i}"] = $hand->getAsString();
        }
        $data["deckCount"] = $deck->getCount();
        $session->set("card_deck", $deck);

        $this->setResponse($data);

        return $this->response;
    }



    // #[Route("/api/deck/draw/{number<\d+>}", name: "api_deck_draw_num", methods: ["POST"])]
    // public function apiDeckDrawNum(
    //     int $number,
    //     SessionInterface $session
    //     ): JsonResponse
    // {
    //     $deck = $session->get("deck") ?? null;
    //     if (!$deck) {
    //         $deck = new CardDeck(Card::class);
    //     }

    //     $hand = new CardHand();
    //     $hand->draw($deck, $number);
    //     $session->set("card_deck", $deck);

    //     $data = [
    //         "hand" => $hand->getAsString(),
    //         "deckCount" => $deck->getCount(),
    //     ];

    //     $this->setResponse($data);

    //     return $this->response;
    // }
}

// json_encode($deck)
