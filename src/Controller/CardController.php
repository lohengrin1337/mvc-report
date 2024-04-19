<?php

namespace App\Controller;

use App\Card\CardDeck;
use App\Card\CardHand;
use App\Card\CardGraphic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller class with routes for card assignment
 */
class CardController extends AbstractController
{
    /**
     * @var array $data - template data
     */
    private $data;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = [
            "siteTitle" => "MVC",
            "pageTitle" => "",
            "cardDraw" => [],
            "allCards" => [],
            "deckCount" => [],
            "players" => [],
        ];
    }



    /**
     * Set flash if $num is greater than card count of deck
     * Draw a number of cards from deck to hand
     * Set string representation of the hand to data["cardDraw"]
     * Set card count of deck to data["deckCount"]
     *
     * @param CardDeck $deck - a CardDeck instance
     * @param CardHand $hand - a CardHand instance
     * @param int $num - a number of cards to draw
     */
    private function drawCards(CardDeck $deck, CardHand $hand, int $num = 1): void
    {
        $deckCount = $deck->getCount();
        if ($num > $deckCount) {
            $this->addFlash(
                'warning',
                "Det fanns {$deckCount} kort kvar i leken!"
            );
        }

        $hand->draw($deck, $num);

        $this->data["hand"] = $hand->getAsString();
        $this->data["deckCount"] = $deck->getCount();
    }



    #[Route("/card", name: "card_start", methods: ["GET"])]
    public function start(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Kortlek";

        // check for deck in session, and create new (and shuffle) if neccessary
        $deck = $session->get("card_deck") ?? null;
        if (!$deck) {
            $deck = new CardDeck(CardGraphic::class);
            $deck->shuffle();
            $session->set("card_deck", $deck);
        }

        return $this->render("card/start.html.twig", $this->data);
    }



    #[Route("/card/deck", name: "card_deck", methods: ["GET"])]
    public function deck(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Visa Kortlek";

        $deck = $session->get("card_deck") ?? null;
        if (!$deck) {
            return $this->redirectToRoute("card_start");
        }

        $deck->sort();
        $session->set("card_deck", $deck);
        $this->data["allCards"] = $deck->getAsString();

        return $this->render("card/deck.html.twig", $this->data);
    }



    #[Route("/card/deck/shuffle", name: "card_deck_shuffle", methods: ["GET"])]
    public function deckShuffle(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Visa blandad Kortlek";

        // $deck = $session->get("card_deck") ?? null;
        // if ($deck) {
        //     $deck->shuffle();
        //     $session->set("card_deck", $deck);
        //     $this->data["allCards"] = $deck->getAsString();
        // }

        $deck = new CardDeck(CardGraphic::class);
        $deck->shuffle();
        $session->set("card_deck", $deck);
        $this->data["allCards"] = $deck->getAsString();

        return $this->render("card/deck_shuffle.html.twig", $this->data);
    }



    #[Route("/card/deck/draw", name: "card_deck_draw", methods: ["GET"])]
    public function deckDraw(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Dra ett kort";

        $deck = $session->get("card_deck") ?? null;
        if (!$deck) {
            return $this->redirectToRoute("card_start");
        }

        $this->drawCards($deck, new CardHand());
        $session->set("card_deck", $deck);
        return $this->render("card/deck_draw.html.twig", $this->data);
    }



    #[Route("/card/deck/draw/{number<\d+>}", name: "card_deck_draw_num", methods: ["GET"])]
    public function deckDrawNum(
        int $number,
        SessionInterface $session
    ): Response {
        $this->data["pageTitle"] = "Dra {$number} kort";

        $deck = $session->get("card_deck") ?? null;
        if (!$deck) {
            return $this->redirectToRoute("card_start");
        }

        $this->drawCards($deck, new CardHand(), $number);
        $session->set("card_deck", $deck);
        return $this->render("card/deck_draw.html.twig", $this->data);
    }



    #[Route("/card/deck/deal/{players<\d+>}/{cards<\d+>}", name: "card_deck_deal", methods: ["GET"])]
    public function deckDeal(
        int $players,
        int $cards,
        SessionInterface $session
    ): Response {
        $this->data["pageTitle"] = "Dra {$cards} kort till {$players} spelare";

        $deck = $session->get("card_deck") ?? null;
        if (!$deck) {
            return $this->redirectToRoute("card_start");
        }

        for ($i = 1; $i <= $players; $i++) {
            $hand = new CardHand();
            $this->drawCards($deck, $hand, $cards);
            $this->data["players"]["Spelare {$i}"] = $hand->getAsString();
        }
        $session->set("card_deck", $deck);

        return $this->render("card/deck_deal.html.twig", $this->data);
    }
}
